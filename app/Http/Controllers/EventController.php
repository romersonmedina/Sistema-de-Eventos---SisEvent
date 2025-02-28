<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use App\Models\Event;

use App\Models\User;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\search;

use App\Mail\AttendanceLiberated;

use App\Models\Feedback;

use App\Exports\ParticipantsExport;

use Chartjs;

class EventController extends Controller
{
    public function index(Request $request) {

        // Se houver pesquisa
        $search = $request->input('search');
        $eventsQuery = Event::query();

        if ($search) {
            $eventsQuery->where('title', 'like', '%' . $search . '%');
        }

        // Pegue todos os eventos
        $events = $eventsQuery->get();

        $today = Carbon::today(); // Pega apenas a data atual, ignorando o horário

        $upcomingEvents = $events->filter(function ($event) use ($today) {
            return Carbon::parse($event->date)->startOfDay()->greaterThanOrEqualTo($today);
        });

        $pastEvents = $events->filter(function ($event) use ($today) {
            return Carbon::parse($event->date)->startOfDay()->lessThan($today);
        });

        return view('welcome', compact('search', 'upcomingEvents', 'pastEvents'));


    }


    public function create() {
        return view('events.create');
    }


    public function store(Request $request) {

        // Criar o evento após a validação
        $event = new Event;
        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->input('description');
        $event->items = $request->items;

        // Image upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $event->image = $imageName;
        }

        // Associar o evento ao usuário logado
        $user = Auth::user();
        $event->user_id = $user->id;

        // Salvar o evento no banco de dados
        $event->save();

        // Redirecionar para a página inicial com uma mensagem de sucesso
        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }


    public function show($id) {
        $event = Event::findOrFail($id);
        $user = auth()->user();

        $hasUserJoined = false;

        if ($user) {
            $hasUserJoined = $user->eventsAsParticipant()
                ->wherePivot('event_id', $id)
                ->wherePivot('cancelled', false)
                ->exists();
        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', [
            'event' => $event,
            'eventOwner' => $eventOwner,
            'hasUserJoined' => $hasUserJoined
        ]);
    }



    public function dashboard()
    {
    $user = auth()->user();
    $today = Carbon::today();

    // Obtém os eventos criados pelo usuário (eventos que o usuário é organizador)
    $allEvents = Event::where('user_id', $user->id)->get();

    // Filtra eventos futuros que o usuário está organizando
    $events = $allEvents->filter(function ($event) use ($today) {
        return Carbon::parse($event->date)->startOfDay()->greaterThanOrEqualTo($today);
    });

    // Filtra eventos passados que o usuário está organizando
    $pastEvents = $allEvents->filter(function ($event) use ($today) {
        return Carbon::parse($event->date)->startOfDay()->lessThan($today);
    });

    // Obtém os eventos nos quais o usuário está inscrito, sem cancelamentos
    $eventsAsParticipant = $user->eventsAsParticipant()
        ->wherePivot('cancelled', false)  // Certifica que o evento não foi cancelado
        ->where('date', '>=', $today)    // Filtro para garantir que mostre apenas eventos futuros
        ->get();

    // Se você precisar de eventos passados que o usuário participou, pode criar outro filtro
    $pastEventsAsParticipant = $user->eventsAsParticipant()
        ->wherePivot('cancelled', false)  // Certifica que o evento não foi cancelado
        ->where('date', '<', $today)      // Filtro para eventos passados
        ->get();

    return view('events.dashboard', compact('events', 'pastEvents', 'eventsAsParticipant', 'pastEventsAsParticipant'));
    }


    public function destroy($id) {
        // Encontrar o evento
        $event = Event::findOrFail($id);

        // Remover os registros associados à tabela event_user
        $event->users()->detach(); // Remove todos os registros da tabela pivot

        // Excluir o evento
        $event->delete();

        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function edit ($id) {
        $user = auth()->user();

        $event = Event::findOrFail($id);

        if($user->id != $event->user_id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update (Request $request){
        $data = $request->all();

        // Image upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }
        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function joinEvent($id) {
        $user = auth()->user();
        $event = Event::findOrFail($id);

        // Verifica se o usuário já está inscrito no evento
        $pivot = $user->eventsAsParticipant()->wherePivot('event_id', $id)->first();

        if ($pivot) {
            // Se o status do evento estiver 'cancelado', remove o cancelamento e permite a reinscrição
            if ($pivot->pivot->cancelled) {
                $user->eventsAsParticipant()->updateExistingPivot($id, ['cancelled' => false]);
                return redirect('/dashboard')->with('msg', 'Sua inscrição foi reativada no evento ' . $event->title);
            }
            // Se o usuário já estiver inscrito e não cancelado, não faz nada
            return redirect('/dashboard')->with('msg', 'Você já está inscrito no evento ' . $event->title);
        }

        // Caso o usuário não tenha inscrição, inscreve ele no evento
        $user->eventsAsParticipant()->attach($id);

        // Incrementa a contagem de participantes
        $event->increment('participants_count');

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' . $event->title);
    }

    public function leaveEvent($id) {
        $user = auth()->user();
        $event = Event::findOrFail($id);

        // Verifica se o usuário está inscrito no evento
        if ($user->eventsAsParticipant()->where('event_id', $id)->exists()) {
            // Marca o evento como cancelado para o usuário
            $user->eventsAsParticipant()->updateExistingPivot($id, ['cancelled' => true]);

            // Diminui a contagem de participantes no evento
            $event->decrement('participants_count');
        }

        return redirect('/dashboard')->with('msg', 'Você saiu do evento ' . $event->title);
    }


    public function showParticipants($id) {

            // Encontre o evento pelo ID e carregue os usuários inscritos
            $event = Event::with(['users' => function($query) {
                $query->orderBy('name'); // Ordena os participantes pelo nome
            }])->findOrFail($id);

            // Verifique se o usuário logado é o criador do evento
            if (auth()->user()->id !== $event->user_id) {
                return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar essa página.');
            }

            return view('events.participants', compact('event'));
    }

    public function attendance($id) {

        $event = Event::find($id);

        // Verifica se a lista de presença está aberta
        if (!$event->attendance_open) {
            return redirect()->back()->with('error', 'A lista de presença não está aberta ainda.');
        }

        return view('events.attendance', compact('event'));
    }

    public function signAttendance($id) {

        $event = Event::find($id);

        // Verifica se o usuário já assinou
        if ($event->users->contains(auth()->user())) {
            return redirect()->back()->with('error', 'Você já assinou a lista de presença.');
        }

        // Adiciona o usuário à lista de presença
        $event->users()->attach(auth()->user()->id);

        return redirect()->route('events.attendance', $event->id)->with('success', 'Presença assinada com sucesso!');
    }

    public function toggleAttendance($id){

        $event = Event::find($id);

        if ($event) {
            // Liberar a lista de presença (atualizar no banco)
            $event->attendance_open = true;
            $event->save();

            // Buscar todos os participantes do evento
            $users = User::whereIn('id', function ($query) use ($event) {
                $query->select('user_id')->from('event_user')->where('event_id', $event->id);
            })->get();

            // Coletar todos os e-mails dos usuários
            $emails = $users->pluck('email')->toArray();

            // Enviar e-mail para todos os participantes
            foreach ($users as $user) {
                if (!empty($user->email)) {
                    Mail::to($user->email)->send(new AttendanceLiberated($event, $user));
                }
            }

            return redirect()->back()->with('message', 'Lista de presença liberada com sucesso!');
        }

        return redirect()->back()->with('error', 'Evento não encontrado!');
    }


    public function markAttendance(Request $request, $id) {
        $userId = $request->query('token');

        $event = Event::findOrFail($id);
        $user = User::findOrFail($userId);

        // Verifica se o usuário já marcou presença
        if ($event->users()->where('user_id', $user->id)->exists()) {
            return redirect('/')->with('message', 'Você já marcou presença nesse evento.');
        }

        // Marcar presença no evento
        $event->users()->attach($user->id, ['attended' => true]);

        return redirect('/')->with('message', 'Presença confirmada com sucesso!');
    }

    public function confirmAttendance($id)
    {
        $userId = auth()->user()->id;

        DB::table('event_user')
            ->where('event_id', $id)
            ->where('user_id', $userId)
            ->update(['attended' => true]);

        return redirect()->back()->with('message', 'Presença confirmada com sucesso!');
    }


    public function printAttendance($id)
    {
        // Encontrar o evento
        $event = Event::findOrFail($id);

        // Pegar os participantes que assinaram a lista de presença (considerando que o campo 'attended' é true)
        $attendedUsers = $event->users()
                           ->wherePivot('attended', true)
                           ->orderBy('name')  // Ordena pelo nome
                           ->get();

        // Gerar o PDF com a lista de presença
        $pdf = PDF::loadView('events.print-attendance', compact('event', 'attendedUsers'));

        // Retornar o PDF para download ou exibição
        return $pdf->download("lista_presenca_{$event->title}.pdf");
    }

    public function getGoogleCalendarLink($id)
    {
        $event = Event::findOrFail($id);

        $title = urlencode($event->title);
        $startDate = \Carbon\Carbon::parse($event->start_date)->format('Ymd\THis\Z');
        $endDate = \Carbon\Carbon::parse($event->end_date)->format('Ymd\THis\Z');
        $description = urlencode($event->description);
        $location = urlencode($event->location);

        $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE"
            . "&text={$title}"
            . "&dates={$startDate}/{$endDate}"
            . "&details={$description}"
            . "&location={$location}"
            . "&sf=true&output=xml";

        return redirect($googleCalendarUrl);
    }

    /*
    // Exibir os feedbacks do evento
    public function showFeedbacks($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Verificar se o usuário autenticado é o dono do evento
        if ($event->user_id !== Auth::id()) {
            return redirect()->route('events.index')->with('error', 'Você não tem permissão para ver os feedbacks deste evento.');
        }

        // Obter os feedbacks do evento
        $feedbacks = Feedback::where('event_id', $eventId)->get();

        return view('events.feedbacks', compact('event', 'feedbacks'));
    }

    // Exibir as estatísticas do evento
    public function eventStatistics($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Contar o número de inscritos no evento
        $inscritos = $event->users()->count();

        // Contar a taxa de comparecimento
        $comparecimento = $event->users()->wherePivot('attended', true)->count();

        // Contar os cancelamentos
        $cancelados = $event->users()->wherePivot('cancelled', true)->count();

        // Prepare os dados para o gráfico
        $chartData = [
            'labels' => ['Inscritos', 'Comparecimento', 'Cancelados'],
            'datasets' => [
                [
                    'label' => 'Estatísticas do Evento',
                    'data' => [$inscritos, $comparecimento, $cancelados],
                    'backgroundColor' => ['#4CAF50', '#FF9800', '#F44336'],
                ]
            ]
        ];

        return view('events.statistics', compact('event', 'chartData'));
    }
    */

    public function eventStatistics($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Verificar se o usuário autenticado é o dono do evento para exibir os feedbacks
        if ($event->user_id !== Auth::id()) {
            return redirect()->route('events.index')->with('error', 'Você não tem permissão para ver os feedbacks deste evento.');
        }

        // Obter os feedbacks do evento
        $feedbacks = Feedback::where('event_id', $eventId)->get();

        // Calcular a média das avaliações
        $averageRating = $feedbacks->avg('rating');

        // Contar o número de inscritos no evento
        $inscritos = $event->users()->count();

        // Contar a taxa de comparecimento
        $comparecimento = $event->users()->wherePivot('attended', true)->count();

        // Contar os cancelamentos
        $cancelados = $event->users()->wherePivot('cancelled', true)->count();

        // Prepare os dados para o gráfico de estatísticas
        $chartData = [
            'labels' => ['Inscritos', 'Comparecimento', 'Cancelados'],
            'datasets' => [
                [
                    'label' => 'Estatísticas do Evento',
                    'data' => [$inscritos, $comparecimento, $cancelados],
                    'backgroundColor' => ['#4CAF50', '#FF9800', '#F44336'],
                ]
            ]
        ];

        // Prepare os dados para o gráfico de avaliações
        $ratingData = [
            'labels' => ['1', '2', '3', '4', '5'],
            'datasets' => [
                [
                    'label' => 'Avaliações do Evento',
                    'data' => [
                        $feedbacks->where('rating', 1)->count(),
                        $feedbacks->where('rating', 2)->count(),
                        $feedbacks->where('rating', 3)->count(),
                        $feedbacks->where('rating', 4)->count(),
                        $feedbacks->where('rating', 5)->count()
                    ],
                    'backgroundColor' => ['#FF0000', '#FF7F00', '#FFEA00', '#7FFF00', '#00FF00'],
                ]
            ]
        ];

        return view('events.statistics', compact('event', 'feedbacks', 'chartData', 'ratingData', 'averageRating'));
    }

    //Criar relatório em CSV
    public function exportCsv($eventId)
    {
        return Excel::download(new ParticipantsExport($eventId), 'participantes_evento.csv');
    }


    /*public function yourMethod()
    {
        // Agora você pode usar DB sem problemas
        $userAttended = DB::table('event_user')
            ->where('event_id', $event->id)
            ->where('user_id', auth()->user()->id)
            ->value('attended');

        return view('events.dashboard', compact('userAttended', 'events'));

    }*/


}



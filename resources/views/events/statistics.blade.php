@extends('layouts.main')

@section('title', 'Estatísticas do Evento: ' . $event->title)

@section('content')
    <div class="container">
        <h2>Estatísticas do Evento: {{ $event->title }}</h2>

        <!-- Gráficos de Estatísticas lado a lado -->
        <div style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-bottom: 40px;">
            <div style="width: 45%; max-width: 500px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                <canvas id="eventChart"></canvas>
            </div>

            <div style="width: 45%; max-width: 500px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                <canvas id="ratingChart"></canvas>
            </div>
        </div>

        <!-- Exibir os feedbacks -->
        <h3>Feedbacks</h3>
        <ul>
            @foreach($feedbacks as $feedback)
                <li>
                    <strong>Nota:</strong> {{ $feedback->rating }} - {{ $feedback->comment }}<br>
                    <strong>Participante:</strong> {{ $feedback->user->name }} <!-- Exibe o nome do participante -->
                </li>
            @endforeach
        </ul>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
        <script>
            // Gráfico de Estatísticas do Evento
            var ctx = document.getElementById('eventChart').getContext('2d');
            var chartData = @json($chartData);
            var eventChart = new Chart(ctx, {
                type: 'doughnut',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#333'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.raw + ' participantes';
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Estatísticas de Participação', // Título do gráfico
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            color: '#333', // Cor do título
                            padding: {
                                top: 20,
                                bottom: 20
                            }
                        }
                    }
                }
            });

            // Gráfico de Avaliações do Evento
            var ctx2 = document.getElementById('ratingChart').getContext('2d');
            var ratingData = @json($ratingData);
            var averageRating = {{ round($averageRating, 2) }};  // Média das avaliações

            var ratingChart = new Chart(ctx2, {
                type: 'doughnut',
                data: ratingData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top', // Define a posição da legenda (top, left, right, bottom)
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#333' // Cor da legenda
                            }
                        },
                        title: {
                            display: true,
                            text: 'Avaliações dos Participantes', // Título do gráfico
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            color: '#333', // Cor do título
                            padding: {
                                top: 20,
                                bottom: 20
                            }
                        },
                        // Garantir que o datalabels está sendo exibido corretamente
                        datalabels: {
                            display: true,
                            align: 'center',
                            color: '#333',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            formatter: function() {
                                return "Média das Avaliações: " + averageRating + " / 5";
                            }
                        }
                    }
                }
            });


        </script>
    </div>
@endsection

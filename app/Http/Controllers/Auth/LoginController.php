protected function authenticated(Request $request, $user)
{
    if ($user->isAdmin()) {
        return redirect('/admin/dashboard'); // Redireciona admins para um painel específico
    }

    return redirect('/dashboard'); // Usuários comuns vão para o painel normal
}

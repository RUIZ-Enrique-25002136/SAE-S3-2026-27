<?php

namespace App\Controller;

use App\Models\UserRepository;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;

/**
 * Liste paginée des membres, réservée aux membres connectés.
 */
class MemberController
{
    private const PER_PAGE = 10;

    /**
     * @param UserRepository $users Accès aux comptes utilisateurs
     * @param View           $view  Moteur de templates
     */
    public function __construct(
        private UserRepository $users,
        private View $view,
    ) {}

    /**
     * Affiche une page de la liste des membres (GET /members?page=N).
     */
    public function index(Request $request): Response
    {
        if (empty($_SESSION['user'])) {
            return Response::redirect('/login');
        }

        $total = $this->users->countMembers();
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = filter_var($request->get('page', 1), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $page = $page === false ? 1 : min($page, $pages);

        return $this->view->render('members', [
            'members' => $this->users->findPage(self::PER_PAGE, ($page - 1) * self::PER_PAGE),
            'page'    => $page,
            'pages'   => $pages,
            'total'   => $total,
            'title'   => 'Membres',
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\EventModel;
use App\Models\ForumQuestionModel;
use App\Models\WorkProgramModel;
use App\Models\UserModel;
use App\Models\DivisionModel;

class Home extends BaseController
{
    public function index()
    {
        $postModel     = new PostModel();
        $eventModel    = new EventModel();
        $forumModel    = new ForumQuestionModel();
        $prokerModel   = new WorkProgramModel();
        $userModel     = new UserModel();
        $divisionModel = new DivisionModel();

        // Statistik Counter
        $stats = [
            'total_members'   => $userModel->where('is_active', 1)->countAllResults(),
            'total_divisions' => $divisionModel->where('is_active', 1)->countAllResults(),
            'active_prokers'  => $prokerModel->whereIn('status', ['active', 'completed'])->countAllResults(),
            'answered_qa'     => $forumModel->where('status', 'answered')->countAllResults(),
        ];

        // Data Utama
        $latestPosts    = $postModel->getPublished(3);
        $upcomingEvents = $eventModel->getUpcoming(3);
        $popularQnA     = $forumModel->getFilteredQuestions(null, null, null, 3, 0);
        $divisions      = $divisionModel->where('is_active', 1)->findAll();

        $data = [
            'title'          => 'HIMATIKA Connect — Pusat Informasi & Aspirasi Mahasiswa',
            'stats'          => $stats,
            'latestPosts'    => $latestPosts,
            'upcomingEvents' => $upcomingEvents,
            'popularQnA'     => $popularQnA,
            'divisions'      => $divisions,
        ];

        return view('home', $data);
    }
}

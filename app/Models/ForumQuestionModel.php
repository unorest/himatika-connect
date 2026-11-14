<?php

namespace App\Models;

use CodeIgniter\Model;

class ForumQuestionModel extends Model
{
    protected $table            = 'hmt_forum_questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'body', 'author_name', 'author_nim', 'author_email',
        'is_anonymous', 'category_id', 'status', 'upvotes', 'ip_address',
        'is_pinned', 'moderated_by', 'moderated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getFilteredQuestions(?int $categoryId = null, ?string $search = null, ?string $status = null, int $limit = 10, int $offset = 0)
    {
        $builder = $this->select('hmt_forum_questions.*, hmt_categories.name as category_name, hmt_categories.color as category_color, COUNT(hmt_forum_answers.id) as answer_count')
                        ->join('hmt_categories', 'hmt_categories.id = hmt_forum_questions.category_id', 'left')
                        ->join('hmt_forum_answers', 'hmt_forum_answers.question_id = hmt_forum_questions.id AND hmt_forum_answers.deleted_at IS NULL', 'left')
                        ->where('hmt_forum_questions.status !=', 'rejected')
                        ->groupBy('hmt_forum_questions.id');

        if (!empty($categoryId)) {
            $builder->where('hmt_forum_questions.category_id', $categoryId);
        }

        if (!empty($status)) {
            $builder->where('hmt_forum_questions.status', $status);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('hmt_forum_questions.title', $search)
                    ->orLike('hmt_forum_questions.body', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('hmt_forum_questions.is_pinned', 'DESC')
                       ->orderBy('hmt_forum_questions.upvotes', 'DESC')
                       ->orderBy('hmt_forum_questions.created_at', 'DESC')
                       ->findAll($limit, $offset);
    }

    public function getDetailWithAnswers(int $id)
    {
        $question = $this->select('hmt_forum_questions.*, hmt_categories.name as category_name, hmt_categories.color as category_color')
                         ->join('hmt_categories', 'hmt_categories.id = hmt_forum_questions.category_id', 'left')
                         ->where('hmt_forum_questions.id', $id)
                         ->first();

        if (!$question) {
            return null;
        }

        $answers = $this->db->table('hmt_forum_answers')
                            ->select('hmt_forum_answers.*, hmt_users.name as responder_name, hmt_users.role as responder_role, hmt_divisions.name as division_name')
                            ->join('hmt_users', 'hmt_users.id = hmt_forum_answers.answered_by', 'left')
                            ->join('hmt_divisions', 'hmt_divisions.id = hmt_users.division_id', 'left')
                            ->where('hmt_forum_answers.question_id', $id)
                            ->where('hmt_forum_answers.deleted_at', null)
                            ->orderBy('hmt_forum_answers.is_official', 'DESC')
                            ->orderBy('hmt_forum_answers.created_at', 'ASC')
                            ->get()
                            ->getResultArray();

        $question['answers'] = $answers;
        return $question;
    }
}

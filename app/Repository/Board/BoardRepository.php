<?php

namespace App\Repository\Board;
use App\Models\Board;
use App\Models\BoardComment;
use App\Models\BoardLike;
use Carbon\Carbon;

class BoardRepository
{

    /**
     * 게시글 등록
     *
     * @param array $params 파라미터
     * @return object
     * @throws \Exception 예외 발생 시 처리 (필요 시 throw 가능)
     */
    public function createBoards(array $params)
    {
        try {

            return Board::create($params)->id;

        }catch (\Throwable $e){
            throw $e;
        }
    }


    /**
     * 게시글 조회
     *
     * @param array $params 파라미터
     * @param string $orderBy 정렬
     * @param int $page 페이지
     * @param int $perPage 페이지 노출갯수
     * @return object
     * @throws \Exception 예외 발생 시 처리 (필요 시 throw 가능)
     */
    public function getBoardLists(array $params, string $orderBy, int $page= 1, int $perPage =10)
    {
        try {

            $query = Board::with(['user'])->withCount('likes');

            // 제목
            if(!empty($params['title']))
            {
                $query->where('title', 'like', '%'.$params['title'].'%');
            }

            // 등록날짜검색
            $sdate = $params['sdate'] ? Carbon::parse($params['sdate'])->startOfDay() : null;
            $edate = $params['edate']  ? Carbon::parse($params['edate'])->endOfDay() : null;
            if ($sdate && $edate) {
                $query->whereBetween('created_at', [$sdate, $edate]);
            } elseif ($sdate && !$edate) {
                $query->where('created_at', '>=', $sdate);
            } elseif (!$sdate && $edate) {
                $query->where('created_at', '<=', $edate);
            }

            switch($orderBy){
                case "a": // 등록최근순
                    $query->orderBy("id" , "DESC");
                    break;
                case "b": // 등록낮은순
                    $query->orderBy("id");
                    break;
            }
            //dd($query->ddRawSql());

            return $query->paginate($perPage);


        }catch (\Throwable $e){
            throw $e;
        }
    }
}

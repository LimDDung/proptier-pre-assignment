<?php

namespace App\Repository\Board;
use App\Models\Board;
use App\Models\BoardComment;
use App\Models\BoardLike;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BoardRepository
{

    /**
     * 게시글 등록
     *
     * @param array $params 파라미터
     * @return object
     * @throws \Throwable
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
     * 게시글 목록조회
     *
     * @param array $params 파라미터
     * @param string $orderBy 정렬
     * @param int $page 페이지
     * @param int $perPage 페이지 노출갯수
     * @return object
     * @throws \Throwable
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


    /**
     * 게시글 조회
     *
     * @param int $id 게시글 id
     * @return object
     * @throws \Throwable
     */
    public function getBoardById(int $id)
    {
        try {

            return Board::with(['user'])->withCount('likes')->find($id);

        }catch (\Throwable $e){
            throw $e;
        }
    }

    /**
     * 해당 게시글이 내가 쓴 글인지 여부 확인
     *
     * @param int $boardId   게시글 PK
     * @param int $userId    현재 로그인 사용자 PK
     * @return bool
     */
    public function isMyBoard(int $boardId, int $userId): bool
    {

        try {

            return Board::where('id', $boardId)
                ->where('user_id', $userId)
                ->exists();

        }catch (\Throwable $e){
            throw $e;
        }

    }

    /**
     * 게시글 수정
     *
     * @param int $id 게시글 id
     * @param array $params 파라미터
     * @return object
     * @throws \Throwable
     */
    public function updateBoard(int $id, array $params)
    {
        try {

            return Board::where("id", $id)->update($params);

        }catch (\Throwable $e){
            throw $e;
        }
    }


    /**
     * 게시글 삭제
     *
     * @param int $id 게시글 id
     * @return object
     * @throws \Throwable
     */
    public function deleteBoards(int $id)
    {
        try {

            return Board::where("id", $id)->delete();

        }catch (\Throwable $e){
            throw $e;
        }
    }

    /**
     * 게시글 좋아요 등록/해제
     *
     * @param array $params 파라미터
     * @return object
     * @throws \Throwable
     */
    public function toggleBoardLike(array $params)
    {

        try {
            $ck = BoardLike::where('board_id', $params['board_id'])
                ->where('user_id', $params['user_id'])
                ->first();

            // 이미 좋아요 했다면 삭제
            if ($ck) {
                $ck->delete();
                return false;
            }

            // 신규 라면 좋아요 처리
            BoardLike::create([
                'board_id'   => $params['board_id'],
                'user_id'    => $params['user_id'],
                'ip_address' => $params['ip_address'],
            ]);

            return true;
        }catch (\Throwable $e){

        }

    }

    /**
     * 게시글 댓글 등록
     *
     * @param array $params 파라미터
     * @return object
     * @throws \Throwable
     */
    public function boardCommentCreate(array $params)
    {
        try {

            return BoardComment::create($params);

        }catch (\Throwable $e){
            throw  $e;
        }
    }

    /**
     * 게시글 댓글 조회
     *
     * @param array $params 파라미터
     * @return object
     * @throws \Throwable
     */
    public function findCommentById(int $id): ?BoardComment
    {
        return BoardComment::find($id);
    }

    /**
     * 게시글 모든 댓글 가져오기
     *
     * @param int $boardId
     * @return object
     * @throws \Throwable
     */
    public function getBoardComment(int $boardId)
    {
        try {
            return BoardComment::selectRaw('*, HEX(path_bin) as path_hex')
                ->where('board_id', $boardId)
                ->orderBy('path_bin')
                ->get();
        }catch (\Throwable $e){
            throw $e;
        }


    }

    /**
     * 게시글 다음 댓글 순번  세그먼트 정리
     *
     * @param int $boardId 게시글 id
     * @param int $parentId 부모댓글 id
     * @return object
     * @throws \Throwable
     */
    public function boardCommentNextSiblingSeq(int $boardId, int $parentId)
    {
        try {
            $max = BoardComment::where('board_id', $boardId)
                ->when($parentId, fn($q) => $q->where('parent_id', $parentId),
                    fn($q) => $q->whereNull('parent_id'))
                ->max(DB::raw("CONV(HEX(RIGHT(path_bin, 4)), 16, 10)"));

            return (int)($max ?? 0) + 1;

        }catch (\Throwable $e){
            throw $e;
        }

    }



}

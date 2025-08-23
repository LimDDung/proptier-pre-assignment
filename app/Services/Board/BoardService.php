<?php

namespace App\Services\Board;

use App\Repository\Board\BoardRepository;
use Illuminate\Support\Facades\Log;

class BoardService
{
    public function __construct(private readonly BoardRepository $boardRepository)
    {

    }


    /**
     * 게시글 등록
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function createBoard($request)
    {
        try {

            $params = [
                "user_id" => $request->user()->id,
                "title" => $request->input("title"),
                "content" => $request->input("content"),
            ];
            $boards = $this->boardRepository->createBoards($params);

            return [
                "code" => 200,
                "message" => "게시글이 등록되었습니다.",
                "data" => [],
            ];

        }catch (\Exception $e){

            Log::error('[게시글 등록 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "게시글 등록중 오류 발생",
                "data"    => null,
            ];
        }
    }


    /**
     * 게시글 목록조회
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function getBoardLists($request)
    {
        try {

            $params = [
                "title" => $request->input("title"),
                "sdate" => $request->input("sdate"),
                "edate" => $request->input("edate"),
            ];

            $orderBy = $request->input("order_by" ,'a');
            $page = $request->input("page" ,1);
            $perPage = $request->input("per_page", 10);

            $list = $this->boardRepository->getBoardLists($params, $orderBy , $page ,$perPage);

            # 리턴 정리
            $mapped = $list->map(function ($item) {

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'content' => $item->title,
                    "like_count" => $item->likes_count,
                    'created_at' => $item->created_at,
                ];
            });

            return [
                "code" => 200,
                "message" => "게시글 목록 조회 성공.",
                "data" => [
                    'page_info' =>  [
                        'total_count' => $list->total(),
                        'page' => $list->currentPage(),
                        'last_page' => $list->lastPage(),
                    ],
                    'list' => $mapped
                ],
            ];

        }catch (\Exception $e){

            Log::error('[게시글 목록조회 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "게시글 목록조회중 오류 발생",
                "data"    => null,
            ];
        }
    }

}

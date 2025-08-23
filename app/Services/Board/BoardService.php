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
    public function createBoard($request): array
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
                "data" => [
                    "id" => $boards
                ],
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
    public function getBoardLists($request): array
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
                "code" => 500,
                "message" => "게시글 목록조회중 오류 발생",
                "data" => null,
            ];
        }
    }


    /**
     * 게시글 상세조회
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function getBoardDetail($request): array
    {
        try {

            $user = $request->user();
            $boardId = $request->id;
            #본인이 작성한 글인지 체크
            if (!$this->boardRepository->isMyBoard($boardId, $user->id)) {
                return [
                    'code' => 403,
                    'message' => '작성자 본인만 조회 가능합니다.',
                    'data' => null,
                ];
            }

            # 상세조회
            $detail = $this->boardRepository->getBoardById($boardId);
            # 리턴 정리
            $data =   [
                'id' => $detail->id,
                'title' => $detail->title,
                'content' => $detail->title,
                "like_count" => $detail->likes_count,
                'created_at' => $detail->created_at,
            ];

            return [
                "code" => 200,
                "message" => "게시글 상세조회 되었습니다.",
                "data" => $data
            ];


        }catch (\Throwable $e){

            Log::error('[게시글 상세조회 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "게시글 상세조회중 오류 발생",
                "data"    => null,
            ];
        }
    }

    /**
     * 게시글 수정
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function updateBoard($request)
    {
        try {

            $user = $request->user();
            $boardId = $request->id;
            #본인이 작성한 글인지 체크
            if (!$this->boardRepository->isMyBoard($boardId, $user->id)) {
                return [
                    'code' => 403,
                    'message' => '작성자 본인만 수정 가능합니다.',
                    'data' => null,
                ];
            }

            # 게시글 수정
            $params =[];
            if($request->filled("title")){
                $params['title'] = $request->input("title");
            }
            if($request->filled("content")){
                $params['content'] = $request->input("content");
            }

            if(!empty($params))
            {
                $this->boardRepository->updateBoard($boardId, $params);
            }

            return [
                "code"    => 200,
                "message" => "게시글 수정 되었습니다.",
                "data"    => [
                    "id" => $boardId
                ],
            ];


        }catch (\Throwable $e){

            Log::error('[게시글 수정 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "게시글 수정중 오류 발생",
                "data"    => null,
            ];
        }
    }

    /**
     * 게시글 삭제
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function deleteBoard($request)
    {
        try {


            $user = $request->user();
            $boardId = $request->id;
            #본인이 작성한 글인지 체크
            if (!$this->boardRepository->isMyBoard($boardId, $user->id)) {
                return [
                    'code' => 403,
                    'message' => '작성자 본인만 삭제 가능합니다.',
                    'data' => null,
                ];
            }

            # 게시글 삭제
            $this->boardRepository->deleteBoards($request->id);

            return [
                "code"    => 200,
                "message" => "게시글 삭제 되었습니다.",
                "data"    => [
                ],
            ];


        }catch (\Throwable $e){

            Log::error('[게시글 삭제 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "게시글 삭제중 오류 발생",
                "data"    => null,
            ];
        }
    }


    /**
     * 게시글 좋아요 등록
     *
     * @param $request
     * @return array
     * @throws \Throwable
     */
    public function toggleBoardLike($request)
    {
        try {

            # 게시글 좋아요 등록/해제
            $params = [
                "board_id" => $request->input("board_id"),
                "user_id" => $request->user()->id,
                "ip_address" =>  $request->ip(),
            ];
            $liked = $this->boardRepository->toggleBoardLike($params);

            return [
                "code" => 200,
                'message' => $liked ? '좋아요가 등록되었습니다.' : '좋아요가 해제되었습니다.',
                "data" => null,
            ];


        }catch (\Throwable $e){

            Log::error('[게시글 좋아요 증가 Error!!]', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "code"    => 500,
                "message" => "게시글 좋아요 증가 처리중 오류 발생",
                "data"    => null,
            ];
        }
    }
}

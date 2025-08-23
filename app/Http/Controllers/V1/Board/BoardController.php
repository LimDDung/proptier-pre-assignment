<?php

namespace App\Http\Controllers\V1\Board;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\Board\BoardService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;
use Psy\Util\Json;

class BoardController extends Controller
{
    public function __construct(private readonly BoardService $boardService)
    {

    }


    /**
     * 게시글 생성
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function boardCreate(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'title'   => ['required', 'string', 'max:150'],
            'content' => ['required', 'string'],
        ], [
            'title.required'   => '제목은 필수 입력 항목입니다.',
            'title.max'        => '제목은 최대 150자까지 가능합니다.',
            'content.required' => '내용은 필수 입력 항목입니다.',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 500,'입력값 검증 실패', $validator->errors());
        }

        $rt = $this->boardService->createBoard($request);

        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_CREATED, $code, $message, $data);
    }

    /**
     * 게시글 목록조회
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function boardLists(Request $request): JsonResponse
    {

        $rt = $this->boardService->getBoardLists($request);

        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_OK, $code, $message, $data);

    }


    /**
     * 게시글 수정
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function boardUpdate(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        // 서비스에서 작성자 본인 여부 체크
        $rt = $this->boardService->updateBoard($request, $id, $user);
        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_OK, $code, $message, $data);
    }

    /**
     * 게시글 삭제
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function boardDelete(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $rt = $this->boardService->deleteBoard($id, $user);
        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_OK, $code, $message, $data);
    }
}

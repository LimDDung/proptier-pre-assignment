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
     * 게시글 상세조회
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function boardDetail(Request $request): JsonResponse
    {

        $rt = $this->boardService->getBoardDetail($request);

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
    public function boardUpdate(Request $request): JsonResponse
    {

        $rt = $this->boardService->updateBoard($request);
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
    public function boardDelete(Request $request): JsonResponse
    {

        $rt = $this->boardService->deleteBoard($request);
        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_OK, $code, $message, $data);
    }


    /**
     * 게시글 좋아요 카운트 증가
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function boardLikeCreate(Request $request): JsonResponse
    {


        $rt = $this->boardService->toggleBoardLike($request);
        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_CREATED, $code, $message, $data);
    }

    /**
     * 게시글 댓글 등록
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function boardComment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'content'   => ['required','string','min:1'],
        ], [
            'content.required' => '댓글 내용을 입력해주세요.',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 500, '입력값 검증 실패', $validator->errors());
        }

        $rt = $this->boardService->createComment($request);
        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_CREATED, $code, $message, $data);
    }


    /**
     * 게시글 댓글 조회
     *
     * @param Request $request HTTP 요청 객체
     *
     * @return JsonResponse 결과응답
     */
    public function boardCommentGet(Request $request)
    {

        $rt = $this->boardService->getBoardComments($request);
        $code = $rt['code'];
        $message = $rt['message'];
        $data = $rt['data'] ?? [];

        return $this->apiResponse(Response::HTTP_OK, $code, $message, $data);
    }
}

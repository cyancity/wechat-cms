<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/29
 * Time: 下午8:41
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use App\Repositories\LinkRepository;

class UploadController extends ApiController
{
    protected $manager;

    protected $link;

    public function __construct(LinkRepository $link)
    {
        parent::__construct();

        $this->manager = app('uploader');

        $this->link = $link;
    }

    public function index(Request $request)
    {
        $data = $this->manager->folderInfo($request->get('folder'));

        return $this->response->json(['data' => $data]);
    }

    public function uploadForManager(Request $request)
    {
        $strategy = $request->get('strategy', 'images');

        if (!$request->hasFile('image')) {
            return $this->response->json([
                'success' => false,
                'error' => 'no file found'
            ]);
        }
        $path = $strategy.'/'.date('Y').'/'.date('m').'/'.date('d');

        $result = $this->manager->store($request->file('image'), $path);

        return $this->response->json($result);
    }

    public function createFolder(Request $request)
    {
        $folder = $request->get('folder');

        $data = $this->manager->createFolder($folder);

        return $this->response->json(['data' => $data]);
    }

    public function deleteFolder(Request $request)
    {
        $del_folder = $request->get('del_folder');

        $folder = $request->get('folder').'/'.$del_folder;

        $data = $this->manager->deleteFolder($folder);

        if (!data) {
            return $this->response->withForbidden('The directory must be empty to delete it');
        }

        return $this->response->json(['data' => $data]);
    }

    public function deleteFile(Request $request)
    {
        $path = $request->get('path');

        $data = $this->manager->deleteFile($path);

        return $this->response->json(['data' => $data]);
    }
}
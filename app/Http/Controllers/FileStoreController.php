<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class FileStoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $receiver = new FileReceiver(
            UploadedFile::fake()->createWithContent('file', $request->getContent()),
            $request,
            ContentRangeUploadHandler::class
        );

        $save = $receiver->receive();

        if ($save->isFinished()) {
            $this->storeFile($request, $save->getFile());
        }

        $save->handler();
    }

    protected function storeFile(Request $request, UploadedFile $file)
    {
        $request->user()->files()->create([
            'file_path' => $file->storeAs('videos', Str::uuid(), 'public')
        ]);
    }
}

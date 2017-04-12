<?php

namespace LaravelEnso\LogManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class LogManagerController extends Controller
{
    public function index()
    {
        $files = \File::files(storage_path('logs'));
        $logs = [];

        foreach ($files as $file) {
            if (substr($file, -4) == '.log') {
                $logs[] = [

                    'path'             => $file,
                    'filename'         => last(explode('/', $file)),
                    'fileSize'         => round((int) \File::size($file) / 1048576, 2).' MB',
                    'lastModifiedDate' => Carbon::createFromTimestamp(\File::lastModified($file))->format('d-m-Y'),
                    'lastModifiedTime' => Carbon::createFromTimestamp(\File::lastModified($file))->format('H:i:s'),
                ];
            }
        }

        $logs = array_reverse($logs);

        $hasActionButtons = [

            'show'     => request()->user()->hasAccessTo('system.logs.show') ?: false,
            'edit'     => request()->user()->hasAccessTo('system.logs.edit') ?: false,
            'download' => request()->user()->hasAccessTo('system.logs.download') ?: false,
            'delete'   => request()->user()->hasAccessTo('system.logs.destroy') ?: false,
        ];

        return view('laravel-enso/logmanager::index', compact('logs', 'hasActionButtons'));
    }

    public function show($filename)
    {
        $file = storage_path('logs/'.$filename);
        $log = [

            'path'         => $file,
            'filename'     => $filename,
            'fileSize'     => round((int) \File::size($file) / 1048576, 2).' MB',
            'lastModified' => Carbon::createFromTimestamp(\File::lastModified($file))->format('d-m-Y H:i:s'),
            'content'      => \File::get($file),
        ];

        return view('laravel-enso/logmanager::show', compact('log'));
    }

    public function download($filename)
    {
        $headers = ['Content-Type: application/log'];

        return response()->download(storage_path('logs/'.$filename, $filename, $headers));
    }

    public function destroy($log)
    {
        $file = storage_path('logs/'.$log);

        \File::put($file, '');

        $log = [

            'path'             => $file,
            'filename'         => last(explode('/', $file)),
            'fileSize'         => round((int) \File::size($file) / 1048576, 2).' MB',
            'lastModifiedDate' => Carbon::createFromTimestamp(\File::lastModified($file))->format('d-m-Y'),
            'lastModifiedTime' => Carbon::createFromTimestamp(\File::lastModified($file))->format('H:i:s'),
            'message'          => __('Operation was successfull'),
        ];

        return $log;
    }
}
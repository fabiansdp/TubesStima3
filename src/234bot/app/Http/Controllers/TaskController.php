<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    static function KMPSearch($pat, $txt)
    {
        $M = strlen($pat);
        $N = strlen($txt);

        // create lps[] that will hold the longest prefix suffix
        // values for pattern
        $lps=array_fill(0,$M,0);

        // Preprocess the pattern (calculate lps[] array)
        TaskController::computeLPSArray($pat, $M, $lps);

        $i = 0; // index for txt[]
        $j = 0; // index for pat[]
        while ($i < $N) {
            if ($pat[$j] == $txt[$i]) {
                $j++;
                $i++;
            }

            if ($j == $M) {
                return true;
                $j = $lps[$j - 1];
            }

            // mismatch after j matches
            else if ($i < $N && $pat[$j] != $txt[$i]) {
                // Do not match lps[0..lps[j-1]] characters,
                // they will match anyway
                if ($j != 0)
                    $j = $lps[$j - 1];
                else
                    $i++;
            }
        }
        return false;
    }

    // Fills lps[] for given patttern pat[0..M-1]
    static function computeLPSArray($pat, $M, &$lps)
    {
        // length of the previous longest prefix suffix
        $len = 0;

        $lps[0] = 0; // lps[0] is always 0

        // the loop calculates lps[i] for i = 1 to M-1
        $i = 1;
        while ($i < $M) {
            if ($pat[$i] == $pat[$len]) {
                $len++;
                $lps[$i] = $len;
                $i++;
            }
            else // (pat[i] != pat[len])
            {
                // This is tricky. Consider the example.
                // AAACAAAA and i = 7. The idea is similar
                // to search step.
                if ($len != 0) {
                    $len = $lps[$len - 1];

                    // Also, note that we do not increment
                    // i here
                }
                else // if (len == 0)
                {
                    $lps[$i] = 0;
                    $i++;
                }
            }
        }
    }

    function find_by_id($id, $jenis=NULL){
        if ($jenis != NULL)
            $task = DB::table("tasks")->where("id", $id)->where("jenis_task", $jenis);
        else $task = DB::table("tasks")->where("id", $id);
        return $task;
    }

    function find_by_jenis($jenis){
        $task = DB::table("tasks")->where("jenis_task", $jenis);
        return $task;
    }
    
    function showDlTask($user_id, $jenis, $matkul){
        $task = DB::table('tasks')
            ->select('deadline','topik')
            ->where('user_id', $user_id)
            ->where('jenis_task', $jenis)
            ->where('mata_kuliah', $matkul)
            ->orderBy('deadline')
            ->get();
        
        return $task;
    }

    function getDl($user_id, $jenis=NULL){
        if ($jenis == NULL){
            $task = DB::table('tasks')
                ->where('user_id',$user_id)
                ->orderBy('deadline')
                ->get();
        } else {
            $task = DB::table('tasks')
                ->where('jenis_task', $jenis)
                ->where('user_id', $user_id)
                ->orderBy('deadline')
                ->get();
        }

        return $task;
    }

    function getDlBetweenDates($user_id,$tanggal1,$tanggal2, $jenis=NULL){
        if ($jenis == NULL){
            $task = DB::table('tasks')
                ->where('user_id',$user_id)
                ->whereBetween('deadline', [$tanggal1,$tanggal2])
                ->orderBy('deadline')
                ->get();
        } else {
            $task = DB::table('tasks')
                ->where('jenis_task', $jenis)
                ->where('user_id', $user_id)
                ->whereBetween('deadline', [$tanggal1,$tanggal2])
                ->orderBy('deadline')
                ->get();
        }
        return $task;
    }

    function getTask(){
        return Task::all();
    }

    function addTask($userid, $deadline, $matkul, $jenis, $topik) {
        $task = new Task;
        $task->user_id = $userid; // sementara
        $task->deadline = $deadline;
        $task->mata_kuliah = $matkul;
        $task->jenis_task = $jenis;
        $task->topik = $topik;
        $task->save();
    }

    function updateTask($taskID, $tanggal) {
        $task = Task::findOrFail($taskID);
        $task->deadline = $tanggal;
        $task->save();
    }

    function deleteTask($taskID) {
        $task = Task::findOrFail($taskID);
        $task->delete();
    }

    function decideTask(Request $req){
        // asumsi sudah login
        $user_id = $req->user()->id;
        $kodeMatkulPattern = '/[a-zA-z]{2}[0-9]{4}\s/';
        $jenisTugasPattern = '/kuis|praktikum|tu(bes|cil)|ujian/';
        $tanggalPattern = '/[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/';
        $topikPattern = '/([0-9]{4}\s)(.*)[\s][0-9]{4}\b/';
        $taskIdPattern = '/task (\d+)/';
        $fromreq = strtolower($req->message);

        if (preg_match($kodeMatkulPattern, $fromreq, $matkul) 
            && preg_match($jenisTugasPattern, $fromreq, $jenis) 
            && preg_match($tanggalPattern, $fromreq, $tanggal)
        ) {
            preg_match($topikPattern, $fromreq, $topik);
            TaskController::addTask($user_id, $tanggal[0], strtoupper($matkul[0]), ucwords(strtolower($jenis[0])), ucwords($topik[2]));

            return response()->json([
                'type' => 'addTask',
                'msg' => 'Task telah dimasukkan'
            ]);

        }
        // Pembaharuan task
        else if ((TaskController::KMPSearch("diundur", $fromreq) || TaskController::KMPSearch("dimajukan", $fromreq))
            && preg_match($taskIdPattern, $fromreq, $taskID)
            && preg_match($tanggalPattern, $fromreq, $tanggal)
        ) {
            TaskController::updateTask($taskID[1], $tanggal[0]);
            return response()->json([
                'type' => 'updateTask',
                'msg' => 'Task telah diupdate'
            ]);
        }
        
        // Delete task
        else if (TaskController::KMPSearch("selesai", $fromreq) && preg_match($taskIdPattern, $fromreq, $taskID)) {
            TaskController::deleteTask($taskID[1]);
            return response()->json([
                'type' => 'selesaiTask',
                'msg' => 'Task telah diselesaikan'
            ]);
        } 
        
        // Perhitungan deadline
        else if (TaskController::KMPSearch("deadline", $fromreq)) {
            if (TaskController::KMPSearch("sejauh", $fromreq)){
                if (preg_match($jenisTugasPattern, $fromreq, $jenis)) {
                    $task = TaskController::getDl($user_id,$jenis[0]);

                    return response()->json([
                        'type' => 'deadline',
                        'msg' => $task
                    ]);
                } else {
                    $task = TaskController::getDl($user_id);
    
                    return response()->json([
                        'type' => 'deadline',
                        'msg' => $task
                    ]);
                }
            }
            else if (TaskController::KMPSearch("antara", $fromreq)&&preg_match_all($tanggalPattern, $fromreq, $tanggal)){
                $dateTimestamp1 = $tanggal[0][0];
                $dateTimestamp2 = $tanggal[0][1];
                if ($dateTimestamp1 > $dateTimestamp2){
                    if(preg_match($jenisTugasPattern, $fromreq, $jenis)){
                        $task = TaskController::getDlBetweenDates($user_id,$dateTimestamp2,$dateTimestamp1, $jenis[0]);

                        return response()->json([
                            'type' => 'deadline',
                            'msg' => $task
                        ]);
                    }
                    else{
                        $task = TaskController::getDlBetweenDates($user_id,$dateTimestamp2,$dateTimestamp1);

                        return response()->json([
                            'type' => 'deadline',
                            'msg' => $task
                        ]);
                    }
                }
                else {
                    if(preg_match($jenisTugasPattern, $fromreq, $jenis)){
                        $task = TaskController::getDlBetweenDates($user_id,$dateTimestamp1,$dateTimestamp2, $jenis[0]);
                        
                        return response()->json([
                            'type' => 'deadline',
                            'msg' => $task
                        ]);
                    }
                    else{
                        $task = TaskController::getDlBetweenDates($user_id,$dateTimestamp1,$dateTimestamp2);

                        return response()->json([
                            'type' => 'deadline',
                            'msg' => $task
                        ]);
                    }
                }
            } else if (TaskController::KMPSearch("depan", $fromreq)){
                $STR = explode(" ", $fromreq);
                $currDate = date("Y-m-d");
                if (TaskController::KMPSearch("minggu", $fromreq)){
                    $N= array_search("minggu",$STR);
                    $N= $N-1;
                    $date = date("Y-m-d", strtotime("+$N week"));
                    if(preg_match($jenisTugasPattern, $fromreq, $jenis)){
                        $task = TaskController::getDlBetweenDates($user_id,$currDate,$date,$jenis[0]);

                        return response()->json([
                            'type' => 'deadline',
                            'msg' => $task
                        ]);
                    }
                    else{
                        $task = TaskController::getDlBetweenDates($user_id,$currDate,$date);

                        return response()->json([
                            'type' => 'deadline',
                            'msg' => $task
                        ]);
                    }
                    
                }
                else if (TaskController::KMPSearch("hari", $fromreq)){
                    $N= array_search("hari",$STR);
                    $N= $N-1;
                    $date = date("Y-m-d", strtotime("+$N day"));
                    if(preg_match($jenisTugasPattern, $fromreq, $jenis)){
                        $task = TaskController::getDlBetweenDates($user_id,$currDate,$date,$jenis[0]);

                        return response()->json([
                            'type' => 'deadline',
                            'msg' => $task
                        ]);
                    }
                    else{
                        $task = TaskController::getDlBetweenDates($user_id,$currDate,$date);

                        return response()->json([
                            'type' => 'deadline',
                            'msg' => $task
                        ]);
                    }
                } 
            }
            else if (TaskController::KMPSearch("hari ini", $fromreq)) {
                $currDate = date("Y-m-d");
                if (preg_match($jenisTugasPattern, $fromreq, $jenis)) {
                    $task = TaskController::getDlBetweenDates($user_id,$currDate,$currDate, $jenis[0]);

                    return response()->json([
                        'type' => 'deadline',
                        'msg' => $task
                    ]);
                } 
                else {
                    $task = TaskController::getDlBetweenDates($user_id,$currDate,$currDate);

                    return response()->json([
                        'type' => 'deadline',
                        'msg' => $task
                    ]);
                }
            }
            else if (TaskController::KMPSearch("kapan", $fromreq) 
                && preg_match($kodeMatkulPattern, $fromreq, $matkul) 
                && preg_match($jenisTugasPattern, $fromreq, $jenis)) 
            {
                $task = TaskController::showDlTask($user_id,$jenis,$matkul);

                return response()->json([
                    'type' => 'deadlineTgl',
                    'msg' => $task
                ]);
            }
            else {
                $task = TaskController::getDl($user_id);

                return response()->json([
                    'type' => 'deadline',
                    'msg' => $task
                ]);
            }
        }
        // Help Command
        else if (TaskController::KMPSearch("help", $fromreq)) {
            return response()->json([
                'type' => 'help',
                'msg' => 'Tulis Help'
            ]);
        }

        // Maaf tidak tahu command
        else {
            return response()->json([
                'type' => 'nocommand',
                'msg' => 'Maaf command tidak diketahui'
            ]);
        }
    }
}

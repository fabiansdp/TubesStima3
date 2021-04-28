<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

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

    function decideTask(Request $req){
        $kodeMatkulPattern = '/[a-zA-z]{2}[0-9]{4}\s/';
        $jenisTugasPattern = '/[kK][uU][iI][sS]|[pP][rR][aA][kK][tT][iI][kK][uU][mM]|[tT][uU]([bB][eE][sS]|[cC][iI][lL])|[uU][jJ][iI][aA][nN]/';
        $tanggalPattern = '/[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/';
        $topikPattern = '/([0-9]{4}\s)(.*)[\s][0-9]{4}\b/';
        $taskIdPattern = '/task (\d+)/';
        $fromreq = $req->value;

        if (preg_match($kodeMatkulPattern, $fromreq, $matkul) 
            && preg_match($jenisTugasPattern, $fromreq, $jenis) 
            && preg_match($tanggalPattern, $fromreq, $tanggal)
        ) {
            preg_match($topikPattern, $fromreq, $topik);
            TaskController::addTask(1, $tanggal[0], strtoupper($matkul[0]), ucwords(strtolower($jenis[0])), ucwords($topik[2]));

            return redirect('/');
        } else {
            if (TaskController::KMPSearch("deadline", $fromreq)) {
                // 2
                if (TaskController::KMPSearch("sejauh", $fromreq))
                    echo "sejauh";
                else if (TaskController::KMPSearch("antara", $fromreq))
                    echo "antara";
                else if (TaskController::KMPSearch("depan", $fromreq)){
                    if (TaskController::KMPSearch("minggu", $fromreq))
                        echo "minggu";
                    else if (TaskController::KMPSearch("hari", $fromreq))
                        echo "hari";
                    echo "depan";
                }
                else if (TaskController::KMPSearch("hari ini", $fromreq))
                    echo "hari ini";

                // 3
                else if (TaskController::KMPSearch("kapan", $fromreq))
                    echo "kapan loh";

            }
            
            // Pembaharuan task
            if ((TaskController::KMPSearch("diundur", strtolower($fromreq)) || TaskController::KMPSearch("dimajukan", strtolower($fromreq)))
                && preg_match($taskIdPattern, $fromreq, $taskID)
                && preg_match($tanggalPattern, $fromreq, $tanggal)
            ) {
                TaskController::updateTask($taskID[1], $tanggal[0]);
                return redirect("/");
            }

            else if (TaskController::KMPSearch("selesai", strtolower($fromreq))) {
                echo "task selesai";
            }

            else if (TaskController::KMPSearch("help", strtolower($fromreq)))
                echo "help";

            else 
                echo "maaf command tidak diketahui";
            // return redirect("/");
        }
    }
}

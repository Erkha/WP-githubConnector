<?php

class GitRepository
{
    public $account;
    public $repository;
    public $commits;

    public function __construct($account, $repository, $commits)
    {
        // TODO:  replace by setters checking conformity  of values
        $this->account = $account;
        $this->repository = $repository;
        $this->commits = $commits;
    }

    public function display()
    {
        try {
            $formatedLog = $this->getLog();
            return $this->generateDisplay($formatedLog);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function formatDescription($description)
    {
        if (strlen($description)>30) {
           return substr($description,0,30) ."...";
        }
        return $description;
    }

    private function formatDate($date){
        $date = str_replace(['T','Z'],[' ',''],$date);
       // $tz = new DateTimeZone('GMT');
        $date = new DateTime($date);
        $dateNow = new DateTime('now');
        if($date->format('Ymd') == $dateNow->format('Ymd')){
            return $date->format('H:i');
        }
        return $date->format('d-m-y');
    }

    private function generateDisplay($displayedLog)
    {
        ob_start(); ?>
        <div class="widget">
        <p class ="widget-title"> Les dernières activités de : </p>
        <ul>
            <li style="text-align :center;"><?= $this->account?>/<?= $this->repository?></li>
        </ul>
        <ul>
        <?php
        foreach ($displayedLog as $commit) {
            ?>
            <li title ="<?= $commit['fullCommit']?>" style ="font-size: .85em;" >
                <a href="<?= $commit['url']?>">    
                    <?= $commit['description']?> (<?= $commit['date']?>)
                </a>
            </li>
            <?php
        }
        ?>
        </ul>
    </div> <?php
        return ob_get_clean();
    }

    private function getLog()
    {
        $transientName = $this->account . $this->repository . $this->commits;
        $logFromTransient = unserialize (get_transient($transientName));
        if (!$logFromTransient) {
            $client = new \Github\Client();
            $log = $client->api('repo')->commits()->all($this->account , $this->repository, array('sha' => 'master'));
            $log = array_slice($log, 0 ,$this->commits);           
            
            $formatedLog = array();
            foreach ($log as $commit) {
                $formatedLog[]= array(
                    'description'=> $this->formatDescription($commit ['commit']['message']),
                    'fullCommit' => $commit ['commit']['message'],
                    'url' =>$commit ['commit']['url'],
                    'date'=> $this->formatDate($commit ['commit']['author']['date']),
                );
            }
            set_transient($transientName,serialize($formatedLog),20);
            return $formatedLog;
        }
        return $logFromTransient;
    }
}

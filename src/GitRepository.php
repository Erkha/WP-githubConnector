<?php

class GitRepository
{
    public $account;
    public $repository;
    public $commits;


    public function init($account, $repository, $commits)
    {
        $this->setAccount($account)
            ->setRepository($repository)
            ->setCommits($commits);
    }

    public function display($account, $repository, $commits)
    {
        try {
            $this->init($account, $repository, $commits);
            $transientName = $this->account . $this->repository . $this->commits;
            $logFromTransient = unserialize(get_transient($transientName));
            if ($logFromTransient) {
                return $this->displayLog($logFromTransient);
            }
            $log = $this->callGitApi();
            $formatedLog = $this->formatLog($log);
            set_transient($transientName, serialize($formatedLog), 20);
            return $this->displayLog($formatedLog);
        } catch (Exception $e) {
            return  $this->displayError($e);
        }
    }

    private function displayError($e)
    {
        ob_start(); ?>
        <div class="widget">
            <p class="widget-title"> <?= _e('latest activity from:') ?> </p>
            <ul>
                <li style="text-align :center;">
                    <?= $this->account ?>/<?= $this->repository ?>
                    <?= $e->getMessage(); ?>
                </li>
            </ul>
        </div>
        <?php
        
        return ob_get_clean();
        }

        private function displayLog($displayedLog)
        {
            ob_start(); ?>
        <div class="widget">
            <p class="widget-title"> <?= _e('latest activity from:') ?> </p>
            <ul>
                <li style="text-align :center;"><?= $this->account ?>/<?= $this->repository ?></li>
            </ul>
            <ul>
            <?php
            foreach ($displayedLog as $commit) {
                ?>
                <li title="<?= $commit['fullCommit'] ?>" style="font-size: .85em;">
                    <a href="<?= $commit['url'] ?>">
                        <?= $commit['description'] ?> (<?= $commit['date'] ?>)
                    </a>
                </li>
            <?php
            }
            ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }

    private function callGitApi()
    {
        $client = new \Github\Client();
        $response = $client->api('repo')->commits()->all($this->account, $this->repository, array('sha' => 'master'));
        return $response;
    }

    private function formatLog($log)
    {
        $log = array_slice($log, 0, $this->commits);
        $formatedLog = array();
        foreach ($log as $commit) {
            $formatedLog[] = array(
                'description' => $this->formatDescription($commit['commit']['message']),
                'fullCommit' => $commit['commit']['message'],
                'url' => $commit['html_url'],
                'date' => $this->formatDate($commit['commit']['author']['date']),
            );
        }
        return $formatedLog;
    }

    private function formatDescription($description)
    {
        if (strlen($description) > 30) {
            return substr($description, 0, 30) . "...";
        }
        return $description;
    }

    private function formatDate($date)
    {
        $date = str_replace(['T', 'Z'], [' ', ''], $date);
        // $tz = new DateTimeZone('GMT');
        $date = new DateTime($date);
        $dateNow = new DateTime('now');
        if ($date->format('Ymd') == $dateNow->format('Ymd')) {
            return $date->format('H:i');
        }
        return $date->format('d-m-y');
    }

    private function setCommits($commits)
    {
        if (is_numeric($commits)) {
            $this->commits = $commits;
            return $this;
        }
        return new Exception(_e("commits to display must be numeric"));
    }

    private function setRepository($repository)
    {
        $this->repository = htmlspecialchars($repository);
        if (empty($repository)) {
            return new Exception(_e("repository must be a string with at least one character"));
        }
        return $this;
    }

    private function setAccount($account)
    {
        $this->account = htmlspecialchars($account);
        if (empty($account)) {
            return new Exception(_e("account must be a string with at least one character"));
        }
        return $this;
    }
}

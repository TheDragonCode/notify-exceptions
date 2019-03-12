<?php

namespace Helldar\Notifex\Jobs;

use Exception;
use Helldar\Notifex\Abstracts\JobAbstract;
use Helldar\Notifex\Traits\JobsConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;

class JiraJob extends JobAbstract
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable, JobsConfiguration;

    /**
     * @var \Exception
     */
    protected $exception;

    protected $subject;

    public function __construct(Exception $exception, string $subject)
    {
        $this->exception = $exception;

        $this->subject = $subject;
    }

    /**
     * @throws \JsonMapper_Exception
     * @throws \Exception
     */
    public function handle()
    {
        $field   = new IssueField;
        $service = new IssueService($this->getJiraConfiguration());

        $field
            ->setProjectKey($this->getConfig('project_key'))
            ->setIssueType($this->getConfig('issue_type'))
            ->setPriorityName($this->getConfig('priority_name'))
            ->setSummary($this->subject)
            ->setDescription($this->getDescription())
            ->addLabel(Config::get('app.url'))
            ->addLabel(Config::get('app.env'))
            ->addLabel(class_basename($this->exception));

        $service->create($field);
    }

    private function getDescription(): string
    {
        return implode(PHP_EOL, [
            sprintf('*%s*', $this->exception->getMessage()),
            sprintf('_%s:%s_', $this->exception->getFile(), $this->exception->getLine()),
            sprintf('{code:bash}%s{code}', $this->exception->getTraceAsString()),
        ]);
    }

    private function getJiraConfiguration(): ArrayConfiguration
    {
        return new ArrayConfiguration([
            'jiraHost'     => $this->getConfig('host'),
            'jiraUser'     => $this->getConfig('user'),
            'jiraPassword' => $this->getConfig('password'),
        ]);
    }
}

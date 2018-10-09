<?php

namespace Helldar\NotifyExceptions\Jobs;

use Helldar\NotifyExceptions\Abstracts\JobAbstract;
use Helldar\NotifyExceptions\Models\ErrorNotification;
use Helldar\NotifyExceptions\Traits\JobsConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;

class JiraJob extends JobAbstract
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable, JobsConfiguration;

    /**
     * @var \Helldar\NotifyExceptions\Models\ErrorNotification
     */
    protected $item;

    /**
     * JiraJob constructor.
     *
     * @param \Helldar\NotifyExceptions\Models\ErrorNotification $item
     */
    public function __construct(ErrorNotification $item)
    {
        $this->item = $item;
    }

    /**
     * @throws \JsonMapper_Exception
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $field   = new IssueField;
            $service = new IssueService($this->getJiraConfiguration());

            $field
                ->setProjectKey($this->getConfig('project_key'))
                ->setIssueType($this->getConfig('issue_type'))
                ->setPriorityName($this->getConfig('priority_name'))
                ->setSummary($this->getTitle())
                ->setDescription($this->getDescription())
                ->addLabel(config('app.url'))
                ->addLabel(config('app.env'))
                ->addLabel($this->item->parent);

            $service->create($field);
        } catch (JiraException $exception) {
            app('sneaker')->captureException($exception);
        }
    }

    private function getTitle(): string
    {
        $server      = request()->getHost() ?? config('app.url');
        $environment = config('app.env');

        return sprintf('%s | Server - %s | Environment - %s', $this->item->parent, $server, $environment);
    }

    private function getDescription(): string
    {
        return implode(PHP_EOL, [
            sprintf('*%s*', $this->item->exception->getMessage()),
            sprintf('_%s:%s_', $this->item->exception->getFile(), $this->item->exception->getLine()),
            sprintf('{code:bash}%s{code}', $this->item->exception->getTraceAsString()),
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

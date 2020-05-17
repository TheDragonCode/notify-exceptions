<?php

namespace Helldar\Notifex\Jobs;

use Helldar\Notifex\Abstracts\JobAbstract;
use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;

class JiraJob extends JobAbstract
{
    /**
     * @throws \JsonMapper_Exception
     * @throws \Exception
     */
    public function handle()
    {
        $field   = $this->getIssueField();
        $service = $this->getIssueService();

        $field
            ->setProjectKey($this->config('project_key'))
            ->setIssueType($this->config('issue_type'))
            ->setPriorityName($this->config('priority_name'))
            ->setSummary($this->title())
            ->setDescription($this->description())
            ->addLabel($this->host())
            ->addLabel($this->environment())
            ->addLabel($this->classname());

        $service->create($field);
    }

    protected function title(): string
    {
        return sprintf('%s | %s | %s', $this->environment(), $this->host(), $this->classname());
    }

    protected function description(): string
    {
        return implode(PHP_EOL, [
            sprintf('*%s*', $this->message),
            sprintf('_%s:%s_', $this->file, $this->line),
            sprintf('{code:bash}%s{code}', $this->trace_as_string),
        ]);
    }

    protected function getJiraConfiguration(): ArrayConfiguration
    {
        return new ArrayConfiguration([
            'jiraHost'     => $this->config('host'),
            'jiraUser'     => $this->config('user'),
            'jiraPassword' => $this->config('password'),
        ]);
    }

    protected function config(string $key)
    {
        return $this->getConfig(get_class(), $key);
    }

    protected function getIssueField(): IssueField
    {
        return new IssueField();
    }

    /**
     * @throws \JiraRestApi\JiraException
     *
     * @return \JiraRestApi\Issue\IssueService
     */
    protected function getIssueService(): IssueService
    {
        return new IssueService(
            $this->getJiraConfiguration()
        );
    }
}

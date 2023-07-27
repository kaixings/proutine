<?php
namespace Kaixings\Proutine;

class Task {
    protected $taskId;
    protected $coroutine;
    protected $sendValue = null;
    protected $beforeFirstYield = true;
    public function __construct($taskId, \Generator $coroutine) {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }
    public function getTaskId() {
        return $this->taskId;
    }

    //发送数据
    public function setSendValue($sendValue) {
        $this->sendValue = $sendValue;
    }
    public function run() {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }
    public function isFinished() {
        return !$this->coroutine->valid();
    }
}
<?php

namespace app\services;

class TimerService
{
    /** @var int */
    private int $beginTime;

    /** @var int */
    private int $spentTime;

    /**
     * @return $this
     */
    public function start(): self
    {
        $this->beginTime = time();
        $this->spentTime = 0;

        return $this;
    }

    /**
     * @return $this
     */
    public function stop(): self
    {
        if(!$this->spentTime) {
            $this->spentTime = time() - $this->beginTime;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getHours(): int
    {
        $this->stop();

        $hours = $this->convertSecToHours($this->spentTime);
        $this->spentTime -= $this->convertHoursToSec($hours);

        return $hours;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        $this->stop();

        $minutes = $this->convertSecToMin($this->spentTime);
        $this->spentTime -= $this->convertMinToSec($minutes);

        return $minutes;
    }

    /**
     * @return int
     */
    public function getSeconds(): int
    {
        $this->stop();

        $seconds = $this->spentTime;
        $this->spentTime = 0;

        return $seconds;
    }

    /**
     * @param int $minutes
     *
     * @return int
     */
    private function convertMinToSec(int $minutes): int
    {
        return $minutes * 60;
    }

    /**
     * @param int $sec
     *
     * @return int
     */
    private function convertSecToMin(int $sec): int
    {
        return intdiv($sec, 60);
    }

    /**
     * @param int $hours
     *
     * @return int
     */
    private function convertHoursToSec(int $hours): int
    {
        return $hours * 60 * 60;
    }

    /**
     * @param int $sec
     *
     * @return int
     */
    private function convertSecToHours(int $sec): int
    {
        return intdiv($sec, 60 * 60);
    }
}
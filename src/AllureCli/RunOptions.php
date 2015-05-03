<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

/**
 * A simple entity carrying options for Allure run.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class RunOptions
{
    /**
     * Report version to use.
     *
     * @type string
     * @since 0.1.0
     */
    private $reportVersion;
    /**
     * Data sources.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $sources;
    /**
     * Path to location where report has to be stored.
     *
     * @type string
     * @since 0.1.0
     */
    private $reportPath;

    /**
     * Returns reportPath.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public function getReportPath()
    {
        return $this->reportPath;
    }

    /**
     * Sets reportPath.
     *
     * @param string $reportPath ReportPath.
     *
     * @codeCoverageIgnore
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setReportPath($reportPath)
    {
        $this->reportPath = $reportPath;
        return $this;
    }

    /**
     * Returns reportVersion.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public function getReportVersion()
    {
        return $this->reportVersion;
    }

    /**
     * Sets reportVersion.
     *
     * @param string $reportVersion ReportVersion.
     *
     * @codeCoverageIgnore
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setReportVersion($reportVersion)
    {
        $this->reportVersion = $reportVersion;
        return $this;
    }

    /**
     * Returns sources.
     *
     * @codeCoverageIgnore
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Sets sources.
     *
     * @param string[] $sources Sources.
     *
     * @codeCoverageIgnore
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setSources($sources)
    {
        $this->sources = $sources;
        return $this;
    }
}

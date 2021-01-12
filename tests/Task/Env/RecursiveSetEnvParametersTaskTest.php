<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Env;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

/**
 * Tests the RecursiveSetEnvParametersTask
 *
 * @author Johannes Borgwardt <johannes.borgwardt@bestit-online.de>
 * @package BestIt\Mage\Task\Env
 */
class RecursiveSetEnvParametersTaskTest extends TestCase
{
    /**
     * @var RecursiveSetEnvParametersTask
     */
    private RecursiveSetEnvParametersTask $service;
    /**
     * @var  vfsStreamDirectory
     */
    private vfsStreamDirectory $directoryMock;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->directoryMock = vfsStream::setup('Module');
        $distMock = new vfsStreamFile('parameter.xml.dist');
        $distMock->setContent(
            file_get_contents(__DIR__ . '/fixtures/RecursiveSetEnvParametersTask/parameter.xml.dist'),
        );
        $moduleOnePath = new vfsStreamDirectory('ModuleOne');
        $moduleOnePath->addChild($distMock);
        $moduleTwoPath = new vfsStreamDirectory('ModuleTwo');
        $moduleTwoPath->addChild($distMock);

        $this->directoryMock->addChild($moduleOnePath);
        $this->directoryMock->addChild($moduleTwoPath);

        $this->service = new RecursiveSetEnvParametersTask();
        $this->service->setOptions([
            'deleteTargets' => false,
            'fileName' => 'parameter.xml.dist',
            'directory' => vfsStream::url('Module'),
        ]);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testConstants(): void
    {
        $this->assertEquals('.dist', RecursiveSetEnvParametersTask::DIST_FILE_EXTENSION);
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        $this->service->execute();

        $this->assertTrue($this->directoryMock->hasChild('ModuleOne/parameter.xml'));
        $this->assertTrue($this->directoryMock->hasChild('ModuleOne/parameter.xml.dist'));
        $this->assertTrue($this->directoryMock->hasChild('ModuleTwo/parameter.xml'));
        $this->assertTrue($this->directoryMock->hasChild('ModuleTwo/parameter.xml.dist'));

        $mockedFile = $this->directoryMock->getChild('ModuleTwo/parameter.xml');
        assert($mockedFile instanceof vfsStreamFile);

        $this->assertStringEqualsFile(
            __DIR__ . '/fixtures/RecursiveSetEnvParametersTask/parameter.xml.dist',
            $mockedFile->getContent(),
        );
    }

    /**
     * @return void
     */
    public function testGetDefaults(): void
    {
        $defaults = $this->service->getDefaults();

        $this->assertArrayHasKey('deleteTargets', $defaults);
        $this->assertEquals(false, $defaults['deleteTargets']);
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertEquals(
            '[Env] Set parameters from env variables in dist files recursively.',
            $this->service->getDescription(),
        );
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertEquals('env/recursive-set-env-parameters', $this->service->getName());
    }
}

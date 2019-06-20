<?php

namespace BestIt\Mage\Tasks\Env;

use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 * Tests the RecursiveSetEnvParametersTask
 *
 * @package BestIt\Mage\Tasks\Env
 * @author Johannes Borgwardt <johannes.borgwardt@bestit-online.de>
 */
class RecursiveSetEnvParametersTaskTest extends TestCase
{
    /**
     * @var RecursiveSetEnvParametersTask
     */
    private $service;
    /**
     * @var  vfsStreamDirectory
     */
    private $directoryMock;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->directoryMock = vfsStream::setup('Module');
        $distMock = new vfsStreamFile('parameter.xml.dist');
        $distMock->setContent(
            file_get_contents(__DIR__ . '/fixtures/RecursiveSetEnvParametersTask/parameter.xml.dist')
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
            'directory' => vfsStream::url('Module')
        ]);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testConstants()
    {
        $this->assertEquals('.dist', RecursiveSetEnvParametersTask::DIST_FILE_EXTENSION);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->service->execute();

        $this->assertTrue($this->directoryMock->hasChild('ModuleOne/parameter.xml'));
        $this->assertTrue($this->directoryMock->hasChild('ModuleOne/parameter.xml.dist'));
        $this->assertTrue($this->directoryMock->hasChild('ModuleTwo/parameter.xml'));
        $this->assertTrue($this->directoryMock->hasChild('ModuleTwo/parameter.xml.dist'));

        /** @var vfsStreamFile $mockedFile */
        $mockedFile = $this->directoryMock->getChild('ModuleTwo/parameter.xml');

        $this->assertStringEqualsFile(
            __DIR__ . '/fixtures/RecursiveSetEnvParametersTask/parameter.xml.dist',
            $mockedFile->getContent()
        );
    }

    /**
     * @return void
     */
    public function testGetDefaults()
    {
        $defaults = $this->service->getDefaults();

        $this->assertArrayHasKey('deleteTargets', $defaults);
        $this->assertEquals(false, $defaults['deleteTargets']);
    }

    /**
     * @return void
     */
    public function testGetDescription()
    {
        $this->assertEquals(
            '[Env] Set parameters from env variables in dist files recursively.',
            $this->service->getDescription()
        );
    }

    /**
     * @return void
     */
    public function testGetName()
    {
        $this->assertEquals('env/recursive-set-env-parameters', $this->service->getName());
    }
}

<?php

namespace tests\PhotoAlbum\Command;

use PhotoAlbum\Commands\AlbumCommand;
use PhotoAlbum\Repositories\AlbumRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AlbumCommandTest extends TestCase
{
    /** @var AlbumRepository|PHPUnit_Framework_MockObject_MockObject */
    private $albumRepositoryMock;
    /** @var CommandTester */
    private $commandTester;

    protected function setUp()
    {
        $this->albumRepositoryMock = $this->getMockBuilder(AlbumRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $application = new Application();
        $application->add(new AlbumCommand($this->albumRepositoryMock));
        $command = $application->find('list:album');
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown()
    {
        $this->albumRepositoryMock = null;
        $this->commandTester = null;
    }

    public function testExecute()
    {
        $id = 3;

        $ret = [
            ['id' =>'123', 'title' => 'Lorem ipsum dolor sit amet',],
        ];

        $this->albumRepositoryMock
            ->expects($this->once())
            ->method('getPhotosByAlbumId')
            ->with($id)
            ->willReturn($ret);

        $this->commandTester->execute(['album-id' => $id]);

        $this->assertEquals(
            '[123] Lorem ipsum dolor sit amet',
            trim($this->commandTester->getDisplay())
        );
    }

    public function testExecuteShouldShowMessageForEmptyAlbum()
    {
        $id = 0;

        $this->albumRepositoryMock
            ->expects($this->once())
            ->method('getPhotosByAlbumId')
            ->with($id)
            ->willReturn([]);

        $this->commandTester->execute(['album-id' => $id]);

        $this->assertEquals(
            'No photos found for album with id [' . $id . ']',
            trim($this->commandTester->getDisplay())
        );
    }

    public function testExecuteShouldThrowExceptionForInvalidAlbumId()
    {
        $id = 'foo';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(sprintf('Album id [%s] is not valid', $id));

        $this->commandTester->execute(['album-id' => $id]);

    }
}
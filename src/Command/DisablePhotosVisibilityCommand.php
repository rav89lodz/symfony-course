<?php

namespace App\Command;

use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
 
#[AsCommand(name: 'app:photo-visible-false',description: 'Set all photos as private for user')]
class DisablePhotosVisibilityCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Set all photos as private for user')->addArgument('user', InputArgument::REQUIRED, 'User ID is required');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $photoRepository = $this->entityManager->getRepository(Photo::class);
        $photosToSetPrivate = $photoRepository->findBy(['is_public' => 1, 'user' => $input->getArgument('user')]);
        foreach ($photosToSetPrivate as $publicPhoto) {
            $publicPhoto->setIsPublic(0);
            $this->entityManager->persist($publicPhoto);
            $this->entityManager->flush();
        }

        return 0;
    }
}
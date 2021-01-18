<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Command;

use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\CreateUserEvent;
use ConnectHolland\UserBundle\Event\UserCreatedEvent;
use ConnectHolland\UserBundle\UserBundleEvents;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @codeCoverageIgnore WIP
 */
final class UserCreateCommand extends Command
{
    protected static $defaultName = 'connectholland:user:create';

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(EventDispatcherInterface $eventDispatcher, ManagerRegistry $doctrine)
    {
        parent::__construct();

        $this->eventDispatcher = $eventDispatcher;
        $this->doctrine        = $doctrine;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a new user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
            ->addOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive')
            ->addOption('role', 'r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Set the users role(s)', ['ROLE_USER'])
            ->setHelp(<<<'EOT'
The <info>connectholland:user:create</info> command creates a user:
  <info>%command.full_name% email</info>
This interactive shell will ask you for an email and then a password.
You can alternatively specify the email and password as the first and second arguments:
  <info>%command.full_name% example@example.com mypassword</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var string $email */
        $email    = $input->getArgument('email');
        /** @var string $password */
        $password = $input->getArgument('password');
        $enable   = $input->getOption('inactive') !== true;
        $roles    = (array) $input->getOption('role');

        $userClass       = $this->doctrine->getRepository(UserInterface::class)->getClassName();
        $user            = new $userClass();
        $createUserEvent = new CreateUserEvent($user->setEmail($email)->setEnabled($enable)->setRoles($roles), $password);
        /* @scrutinizer ignore-call */
        $this->eventDispatcher->dispatch(UserBundleEvents::CREATE_USER, $createUserEvent);
        if (/* @scrutinizer ignore-deprecated */ $createUserEvent->isPropagationStopped() === false) {
            $userCreatedEvent = new UserCreatedEvent($createUserEvent->getUser());
            /* @scrutinizer ignore-call */
            $this->eventDispatcher->dispatch(UserBundleEvents::USER_CREATED, $userCreatedEvent);
            if (/* @scrutinizer ignore-deprecated */ $userCreatedEvent->isPropagationStopped() === false) {
                $output->writeln(sprintf('Created user <comment>%s</comment>', $email));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];
        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }
        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }
        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}

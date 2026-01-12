<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Création d’un administrateur.',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $output->writeln('Bienvenue dans l’interface de création d’un admin !');

        // --- username ---
        do {
            $usernameQuestion = new Question(
                'Entrez le nom d’utilisateur <comment>- Doit contenir au moins 6 caractères.</comment> : '
            );
            $username = $helper->ask($input, $output, $usernameQuestion);

            if (!$username || strlen($username) < 6) {
                $output->writeln('<error>Nom d’utilisateur invalide, veuillez réessayez !</error>');
                $username = null;
            }
        } while (!$username);

        // --- password ---

        do {
            $passwordQuestion = new Question(
                'Entrez le mot de passe <comment>- Doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.</comment> : '
            );
            $passwordQuestion->setHidden(true);
            $passwordQuestion->setHiddenFallback(false);
            $password = $helper->ask($input, $output, $passwordQuestion);

            // --- password validation ---
            $pattern = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/';
            if (!$password || !preg_match($pattern, $password)) {
                $output->writeln('<error>Mot de passe invalide, veuillez réessayez :</error>');
                $password = null;
            }
        } while (!$password);

        // --- confirm password ---
        do {
            $confirmPasswordQuestion = new Question('Répétez le mot de passe : ');
            $confirmPasswordQuestion->setHidden(true);
            $confirmPasswordQuestion->setHiddenFallback(false);
            $confirmPassword = $helper->ask($input, $output, $confirmPasswordQuestion);

            if ($password !== $confirmPassword) {
                $output->writeln('<error>Les mots de passe ne correspondent pas, veuillez réessayez.</error>');
                $confirmPassword = null;
            }
        } while (!$confirmPassword);

        // Delete existing admin
        $existingAdmin = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($existingAdmin) {
            $this->entityManager->remove($existingAdmin);
            $this->entityManager->flush();
        }

        // Create new admin
        $user = new User();
        $user->setUsername($username);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>Le nouvel administrateur a été crée avec succès !</info>');
        $output->writeln('Nom d’utilisateur : <comment> '.$user->getUsername().'</comment>');
        $output->writeln('Allez maintenant sur la page de connexion <comment>http://127.0.0.1:8000/login</comment> et connectez-vous.');

        return Command::SUCCESS;
    }
}

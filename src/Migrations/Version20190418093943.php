<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190418093943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quiz_member_answers (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, question_id INT NOT NULL, answer JSON NOT NULL COMMENT \'(DC2Type:json_array)\', correct TINYINT(1) NOT NULL, answered_at DATETIME NOT NULL, INDEX IDX_85BEE8B27597D3FE (member_id), INDEX IDX_85BEE8B21E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, description LONGTEXT NOT NULL, correct TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, visible TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_members (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, member_id INT NOT NULL, started_at DATETIME NOT NULL, completed_at DATETIME DEFAULT NULL, questions_count SMALLINT DEFAULT NULL, correct_answered SMALLINT DEFAULT NULL, points DOUBLE PRECISION DEFAULT NULL, INDEX IDX_57FA7F5F853CD175 (quiz_id), INDEX IDX_57FA7F5F7597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, visible TINYINT(1) NOT NULL, INDEX IDX_A412FA92F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (quiz_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), INDEX IDX_6033B00B1E27F6BF (question_id), PRIMARY KEY(quiz_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_member_answers ADD CONSTRAINT FK_85BEE8B27597D3FE FOREIGN KEY (member_id) REFERENCES quiz_members (id)');
        $this->addSql('ALTER TABLE quiz_member_answers ADD CONSTRAINT FK_85BEE8B21E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE quiz_members ADD CONSTRAINT FK_57FA7F5F853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_members ADD CONSTRAINT FK_57FA7F5F7597D3FE FOREIGN KEY (member_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_member_answers DROP FOREIGN KEY FK_85BEE8B21E27F6BF');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B1E27F6BF');
        $this->addSql('ALTER TABLE quiz_member_answers DROP FOREIGN KEY FK_85BEE8B27597D3FE');
        $this->addSql('ALTER TABLE quiz_members DROP FOREIGN KEY FK_57FA7F5F853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('DROP TABLE quiz_member_answers');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz_members');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_question');
    }
}

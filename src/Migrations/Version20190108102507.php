<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190108102507 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, ticket_assign_id INT DEFAULT NULL, message_author_id INT DEFAULT NULL, content LONGTEXT NOT NULL, INDEX IDX_DB021E968E646E1B (ticket_assign_id), INDEX IDX_DB021E969CCCF52D (message_author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E968E646E1B FOREIGN KEY (ticket_assign_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969CCCF52D FOREIGN KEY (message_author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE messages');
    }
}

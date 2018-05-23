<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180523093432 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account (user_id INT NOT NULL, balance INT DEFAULT 0 NOT NULL, version INT DEFAULT 1 NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accounting_entry (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, transaction_id INT DEFAULT NULL, amount INT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DB6C942A9B6B5FBA (account_id), INDEX IDX_DB6C942A2FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accounting_transaction (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, tid VARCHAR(255) NOT NULL, amount INT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E88BF01F624B39D (sender_id), INDEX IDX_E88BF01E92F8F78 (recipient_id), UNIQUE INDEX transaction_id (tid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounting_entry ADD CONSTRAINT FK_DB6C942A9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (user_id)');
        $this->addSql('ALTER TABLE accounting_entry ADD CONSTRAINT FK_DB6C942A2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES accounting_transaction (id)');
        $this->addSql('ALTER TABLE accounting_transaction ADD CONSTRAINT FK_E88BF01F624B39D FOREIGN KEY (sender_id) REFERENCES account (user_id)');
        $this->addSql('ALTER TABLE accounting_transaction ADD CONSTRAINT FK_E88BF01E92F8F78 FOREIGN KEY (recipient_id) REFERENCES account (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounting_entry DROP FOREIGN KEY FK_DB6C942A9B6B5FBA');
        $this->addSql('ALTER TABLE accounting_transaction DROP FOREIGN KEY FK_E88BF01F624B39D');
        $this->addSql('ALTER TABLE accounting_transaction DROP FOREIGN KEY FK_E88BF01E92F8F78');
        $this->addSql('ALTER TABLE accounting_entry DROP FOREIGN KEY FK_DB6C942A2FC0CB0F');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE accounting_entry');
        $this->addSql('DROP TABLE accounting_transaction');
    }
}

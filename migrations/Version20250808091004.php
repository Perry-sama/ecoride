<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250808091004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table review et ajustement des contraintes FK';
    }

    public function up(Schema $schema): void
    {
        // Création table review
        $this->addSql('CREATE TABLE review (
            id INT AUTO_INCREMENT NOT NULL,
            author_id INT NOT NULL,
            driver_id INT NOT NULL,
            trip_id INT NOT NULL,
            content LONGTEXT NOT NULL,
            status VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_794381C6F675F31B (author_id),
            INDEX IDX_794381C6C3423909 (driver_id),
            INDEX IDX_794381C6A5BC2E0E (trip_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE=InnoDB');

        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6C3423909 FOREIGN KEY (driver_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trajet (id) ON DELETE CASCADE');

        // Ajustement clés étrangères reservation
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY IF EXISTS fk_reservation_user');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY IF EXISTS fk_reservation_trajet');

        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id) ON DELETE CASCADE');

        // Ajustement clés étrangères reset_password_request
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY IF EXISTS fk_resetpwd_user');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');

        // Ajustement clés étrangères trajet
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY IF EXISTS fk_trajet_user');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6C3423909');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A5BC2E0E');
        $this->addSql('DROP TABLE review');

        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D12A823');

        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_reservation_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_reservation_trajet FOREIGN KEY (trajet_id) REFERENCES trajet (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT fk_resetpwd_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CA76ED395');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT fk_trajet_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}

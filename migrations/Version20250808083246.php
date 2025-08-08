<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250808081748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Correction des clés étrangères et index sur reservation, reset_password_request, trajet, user, et création de messenger_messages';
    }

    public function up(Schema $schema): void
    {
        // Création table messenger_messages (vérifie si déjà créée)
        $this->addSql('CREATE TABLE messenger_messages (
            id BIGINT AUTO_INCREMENT NOT NULL,
            body LONGTEXT NOT NULL,
            headers LONGTEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_75EA56E0FB7336F0 (queue_name),
            INDEX IDX_75EA56E0E3BD61CE (available_at),
            INDEX IDX_75EA56E016BA31DB (delivered_at),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Reservation : suppression des clés étrangères existantes (avec noms exacts)
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D12A823');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');

        // Modification colonne created_at dans reservation
        $this->addSql('ALTER TABLE reservation CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');

        // Suppression puis création des index sur reservation avec noms corrects
        $this->addSql('DROP INDEX FK_42C84955A76ED395 ON reservation');
        $this->addSql('DROP INDEX FK_42C84955D12A823 ON reservation');
        $this->addSql('CREATE INDEX FK_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('CREATE INDEX FK_42C84955D12A823 ON reservation (trajet_id)');

        // Ajout des contraintes FOREIGN KEY avec options ON DELETE
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_reservation_trajet FOREIGN KEY (trajet_id) REFERENCES trajet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_reservation_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');

        // Reset password request : suppression clé étrangère une seule fois
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY fk_resetpwd_user');

        // Modification colonnes datetime dans reset_password_request
        $this->addSql('ALTER TABLE reset_password_request CHANGE requested_at requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE expires_at expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');

        // Suppression puis création index et FK reset_password_request
        $this->addSql('DROP INDEX fk_resetpwd_user ON reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT fk_resetpwd_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');

        // Trajet : suppression clé étrangère UNE FOIS (nom correct)
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY fk_trajet_user');

        // Modification colonnes trajet
        $this->addSql('ALTER TABLE trajet CHANGE description description LONGTEXT DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL');

        // Suppression puis création index et FK trajet
        $this->addSql('DROP INDEX fk_trajet_user ON trajet');
        $this->addSql('CREATE INDEX IDX_2B5BA98CA76ED395 ON trajet (user_id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT fk_trajet_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');

        // User : modification colonnes
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE is_verified is_verified TINYINT(1) NOT NULL');

        // Index user email : suppression et création avec nouveau nom UNIQUE
        $this->addSql('DROP INDEX email ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // Suppression table messenger_messages
        $this->addSql('DROP TABLE messenger_messages');

        // Reservation : suppression contraintes FK (avec noms corrects)
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_reservation_trajet');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_reservation_user');

        // Modification colonne created_at reservation
        $this->addSql('ALTER TABLE reservation CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP');

        // Suppression puis création index reservation à l’état initial
        $this->addSql('DROP INDEX FK_42C84955D12A823 ON reservation');
        $this->addSql('DROP INDEX FK_42C84955A76ED395 ON reservation');
        $this->addSql('CREATE INDEX fk_reservation_trajet ON reservation (trajet_id)');
        $this->addSql('CREATE INDEX fk_reservation_user ON reservation (user_id)');

        // Ajout contraintes FK reservation à l’état initial
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_reservation_trajet FOREIGN KEY (trajet_id) REFERENCES trajet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_reservation_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');

        // Reset password request : suppression FK une fois
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY fk_resetpwd_user');

        // Modification colonnes datetime reset_password_request à l’état initial
        $this->addSql('ALTER TABLE reset_password_request CHANGE requested_at requested_at DATETIME NOT NULL, CHANGE expires_at expires_at DATETIME NOT NULL');

        // Suppression puis création index et FK reset_password_request à l’état initial
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395 ON reset_password_request');
        $this->addSql('CREATE INDEX fk_resetpwd_user ON reset_password_request (user_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT fk_resetpwd_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');

        // Trajet : suppression FK une fois
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY fk_trajet_user');

        // Modification colonnes trajet à l’état initial
        $this->addSql('ALTER TABLE trajet CHANGE description description TEXT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP');

        // Suppression puis création index et FK trajet à l’état initial
        $this->addSql('DROP INDEX IDX_2B5BA98CA76ED395 ON trajet');
        $this->addSql('CREATE INDEX fk_trajet_user ON trajet (user_id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT fk_trajet_user FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');

        // User : modification colonnes à l’état initial
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE is_verified is_verified TINYINT(1) DEFAULT 0 NOT NULL');

        // Index user email à l’état initial
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('CREATE UNIQUE INDEX email ON user (email)');
    }
}

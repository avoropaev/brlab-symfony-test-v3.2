<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191031123254 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE game_buffer (id UUID NOT NULL, game_id UUID NOT NULL, language_id UUID NOT NULL, source_id UUID NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C6F5D3AE48FD905 ON game_buffer (game_id)');
        $this->addSql('CREATE INDEX IDX_4C6F5D3A82F1BAF4 ON game_buffer (language_id)');
        $this->addSql('CREATE INDEX IDX_4C6F5D3A953C1C61 ON game_buffer (source_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C6F5D3AE48FD90582F1BAF4953C1C6195275AB8 ON game_buffer (game_id, language_id, source_id, start_date)');
        $this->addSql('COMMENT ON COLUMN game_buffer.id IS \'(DC2Type:game_source_id)\'');
        $this->addSql('COMMENT ON COLUMN game_buffer.game_id IS \'(DC2Type:game_id)\'');
        $this->addSql('COMMENT ON COLUMN game_buffer.language_id IS \'(DC2Type:language_id)\'');
        $this->addSql('COMMENT ON COLUMN game_buffer.source_id IS \'(DC2Type:source_id)\'');
        $this->addSql('COMMENT ON COLUMN game_buffer.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN game_buffer.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sources (id UUID NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D25D65F2F47645AE ON sources (url)');
        $this->addSql('COMMENT ON COLUMN sources.id IS \'(DC2Type:source_id)\'');
        $this->addSql('COMMENT ON COLUMN sources.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sports (id UUID NOT NULL, display_name VARCHAR(255) NOT NULL, min_interval_between_games INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73C9F91CD5499347 ON sports (display_name)');
        $this->addSql('COMMENT ON COLUMN sports.id IS \'(DC2Type:sport_id)\'');
        $this->addSql('COMMENT ON COLUMN sports.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE sport_names (id UUID NOT NULL, sport_id UUID NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_464F2859AC78BCF8 ON sport_names (sport_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_464F28591D775834 ON sport_names (value)');
        $this->addSql('COMMENT ON COLUMN sport_names.sport_id IS \'(DC2Type:sport_id)\'');
        $this->addSql('CREATE TABLE languages (id UUID NOT NULL, display_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0D15379D5499347 ON languages (display_name)');
        $this->addSql('COMMENT ON COLUMN languages.id IS \'(DC2Type:language_id)\'');
        $this->addSql('COMMENT ON COLUMN languages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE language_names (id UUID NOT NULL, language_id UUID NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D6B4D1182F1BAF4 ON language_names (language_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D6B4D111D775834 ON language_names (value)');
        $this->addSql('COMMENT ON COLUMN language_names.language_id IS \'(DC2Type:language_id)\'');
        $this->addSql('CREATE TABLE leagues (id UUID NOT NULL, sport_id UUID NOT NULL, display_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_85CE39EAC78BCF8 ON leagues (sport_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_85CE39EAC78BCF8D5499347 ON leagues (sport_id, display_name)');
        $this->addSql('COMMENT ON COLUMN leagues.id IS \'(DC2Type:league_id)\'');
        $this->addSql('COMMENT ON COLUMN leagues.sport_id IS \'(DC2Type:sport_id)\'');
        $this->addSql('COMMENT ON COLUMN leagues.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE league_names (id UUID NOT NULL, league_id UUID NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_85DEA67658AFC4DE ON league_names (league_id)');
        $this->addSql('COMMENT ON COLUMN league_names.league_id IS \'(DC2Type:league_id)\'');
        $this->addSql('CREATE TABLE games (id UUID NOT NULL, league_id UUID NOT NULL, team_one_id UUID NOT NULL, team_two_id UUID NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FF232B3158AFC4DE ON games (league_id)');
        $this->addSql('CREATE INDEX IDX_FF232B318D8189CA ON games (team_one_id)');
        $this->addSql('CREATE INDEX IDX_FF232B31E6DD6E05 ON games (team_two_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF232B3158AFC4DE8D8189CAE6DD6E0595275AB8 ON games (league_id, team_one_id, team_two_id, start_date)');
        $this->addSql('COMMENT ON COLUMN games.id IS \'(DC2Type:game_id)\'');
        $this->addSql('COMMENT ON COLUMN games.league_id IS \'(DC2Type:league_id)\'');
        $this->addSql('COMMENT ON COLUMN games.team_one_id IS \'(DC2Type:team_id)\'');
        $this->addSql('COMMENT ON COLUMN games.team_two_id IS \'(DC2Type:team_id)\'');
        $this->addSql('COMMENT ON COLUMN games.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN games.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE teams (id UUID NOT NULL, sport_id UUID NOT NULL, display_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_96C22258AC78BCF8 ON teams (sport_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96C22258AC78BCF8D5499347 ON teams (sport_id, display_name)');
        $this->addSql('COMMENT ON COLUMN teams.id IS \'(DC2Type:team_id)\'');
        $this->addSql('COMMENT ON COLUMN teams.sport_id IS \'(DC2Type:sport_id)\'');
        $this->addSql('COMMENT ON COLUMN teams.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE team_names (id UUID NOT NULL, team_id UUID NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_35350000296CD8AE ON team_names (team_id)');
        $this->addSql('COMMENT ON COLUMN team_names.team_id IS \'(DC2Type:team_id)\'');
        $this->addSql('ALTER TABLE game_buffer ADD CONSTRAINT FK_4C6F5D3AE48FD905 FOREIGN KEY (game_id) REFERENCES games (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_buffer ADD CONSTRAINT FK_4C6F5D3A82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_buffer ADD CONSTRAINT FK_4C6F5D3A953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sport_names ADD CONSTRAINT FK_464F2859AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sports (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE language_names ADD CONSTRAINT FK_8D6B4D1182F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE leagues ADD CONSTRAINT FK_85CE39EAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sports (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE league_names ADD CONSTRAINT FK_85DEA67658AFC4DE FOREIGN KEY (league_id) REFERENCES leagues (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B3158AFC4DE FOREIGN KEY (league_id) REFERENCES leagues (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B318D8189CA FOREIGN KEY (team_one_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31E6DD6E05 FOREIGN KEY (team_two_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teams ADD CONSTRAINT FK_96C22258AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sports (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_names ADD CONSTRAINT FK_35350000296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE game_buffer DROP CONSTRAINT FK_4C6F5D3A953C1C61');
        $this->addSql('ALTER TABLE sport_names DROP CONSTRAINT FK_464F2859AC78BCF8');
        $this->addSql('ALTER TABLE leagues DROP CONSTRAINT FK_85CE39EAC78BCF8');
        $this->addSql('ALTER TABLE teams DROP CONSTRAINT FK_96C22258AC78BCF8');
        $this->addSql('ALTER TABLE game_buffer DROP CONSTRAINT FK_4C6F5D3A82F1BAF4');
        $this->addSql('ALTER TABLE language_names DROP CONSTRAINT FK_8D6B4D1182F1BAF4');
        $this->addSql('ALTER TABLE league_names DROP CONSTRAINT FK_85DEA67658AFC4DE');
        $this->addSql('ALTER TABLE games DROP CONSTRAINT FK_FF232B3158AFC4DE');
        $this->addSql('ALTER TABLE game_buffer DROP CONSTRAINT FK_4C6F5D3AE48FD905');
        $this->addSql('ALTER TABLE games DROP CONSTRAINT FK_FF232B318D8189CA');
        $this->addSql('ALTER TABLE games DROP CONSTRAINT FK_FF232B31E6DD6E05');
        $this->addSql('ALTER TABLE team_names DROP CONSTRAINT FK_35350000296CD8AE');
        $this->addSql('DROP TABLE game_buffer');
        $this->addSql('DROP TABLE sources');
        $this->addSql('DROP TABLE sports');
        $this->addSql('DROP TABLE sport_names');
        $this->addSql('DROP TABLE languages');
        $this->addSql('DROP TABLE language_names');
        $this->addSql('DROP TABLE leagues');
        $this->addSql('DROP TABLE league_names');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE team_names');
    }
}

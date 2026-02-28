-- Extensão para UUID
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Role de aplicação
DO $$ BEGIN IF NOT EXISTS (
    SELECT
    FROM pg_roles
    WHERE
        rolname = 'senac'
) THEN
CREATE ROLE senac LOGIN PASSWORD 'senac';

END IF;

END $$;

-- Bancos adicionais
CREATE DATABASE testing_db OWNER senac;

CREATE DATABASE production_db OWNER senac;

-- Cria um slot de replicação
-- SELECT pg_create_physical_replication_slot('slot_replicacao_master');

-- Cria um backup usando pgbasebackup
--pg_basebackup -h app_master -U senac -D /var/lib/postgresql/data -v -P -X stream -c fast

--primary_conninfo = 'host=app_master port=5432 user=senac password=senac'

-- obtem diretório do PGDATA
--show data_directory;

-- documentações
--https://www.postgresql.org/docs/14/wal-intro.html
--https://www.postgresql.org/docs/14/functions-admin.html
--https://hevodata.com/learn/postgresql-replication-slots/
--https://www.postgresql.org/docs/14/app-pgbasebackup.html
--ATENÇÃO--https://www.postgresql.org/docs/14/hot-standby.html
--https://www.postgresql.org/docs/14/runtime-config-replication.html
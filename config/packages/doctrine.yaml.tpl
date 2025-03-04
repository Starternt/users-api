{{$cluster := or (env "CLUSTER") "production"}}
parameters:
    # Adds a fallback DATABASE_URL if the env var is not set.
    # This allows you to run cache:warmup even if your
    # environment variables are not available yet.
    # You should not need to change this value.
    env(DATABASE_URL): ''

doctrine:
    dbal:
        # configure these for your database server
        driver: 'pdo_mysql'
        server_version: '8.0'
        charset: utf8mb4
        default_table_options:
            charset: utf8mb4
            collate: utf8mb4_unicode_ci{{range $idx, $node := service (printf "%s.mysql" $cluster) }}{{ if eq $idx 0 }}
        host: '{{ $node.Address }}'
        port: {{ $node.Port }}{{ end }}{{ end }}{{with secret "secret/users-database" }}
        dbname: '{{ .Data.dbname }}'
        user: '{{ .Data.user }}'
        password: '{{ .Data.password }}'{{ end }}
        mapping_types:
            enum: string
        persistent: true
        types:
            uuid: Ramsey\Uuid\Doctrine\UuidType

    orm:
        auto_generate_proxy_classes: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            App:
                is_bundle: false
                type: annotation
                dir: '%kernel.project_dir%/src/Entity'
                prefix: 'App\Entity'
                alias: App

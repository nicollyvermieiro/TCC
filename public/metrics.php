<?php
// metrics.php (localizado na pasta /public)

header('Content-type: text/plain; charset=utf-8');

// --- CARREGAMENTO DE ARQUIVOS NECESSÁRIOS ---

// Exceptions (Exceções)
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/Exception/MetricNotFoundException.php';
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/Exception/MetricsRegistrationException.php';

// Interfaces
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/RegistryInterface.php';
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/RendererInterface.php';
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/Storage/Adapter.php';

// Classes Base e Auxiliares
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/Math.php'; 
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/Sample.php';
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/MetricFamilySamples.php';
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/Collector.php';

// Classes Principais
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/CollectorRegistry.php';
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/RenderTextFormat.php';
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/Storage/InMemory.php';
require_once __DIR__ . '/../vendor/prometheus/src/Prometheus/Gauge.php';

// Carrega o arquivo da classe do banco de dados
require_once __DIR__ . '/../config/database.php';

// --- USO DAS CLASSES ---
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

try {
    // Conexão com o banco de dados
    $database = new Database();
    $cn = $database->getConnection();

    // Configuração do Prometheus
    $adapter = new InMemory();
    $registry = new CollectorRegistry($adapter, false); // O 'false' desativa as métricas padrão do PHP

    // --- MÉTRICAS DE NEGÓCIO (do seu banco de dados) ---

    // Métrica 1: Total de usuários
    $gauge_usuarios = $registry->getOrRegisterGauge(
        'manutsmart_app',
        'usuarios_total',
        'Número total de usuários cadastrados.'
    );

    // Métrica 2: Total de chamados
    $gauge_chamados = $registry->getOrRegisterGauge(
        'manutsmart_app',
        'chamados_total',
        'Número total de chamados registrados.'
    );

    // Métrica 3 (NOVA): Total de chamados CONCLUÍDOS
    $gauge_chamados_concluidos = $registry->getOrRegisterGauge(
        'manutsmart_app',
        'chamados_concluidos_total',
        'Número total de chamados concluídos.'
    );

    // Métrica 4 (NOVA VERSÃO): Tempo médio de resolução em SEGUNDOS
    $gauge_tempo_medio_resolucao = $registry->getOrRegisterGauge(
        'manutsmart_app',
        'chamados_tempo_medio_resolucao_segundos', // <-- NOME NOVO E CORRETO
        'Tempo médio, em segundos, para concluir um chamado.'
    );


    // --- BUSCA DOS DADOS NO BANCO ---

    // Contar usuários
    $query_usuarios = $cn->query("SELECT count(*) as total FROM usuario");
    $total_usuarios = $query_usuarios->fetch(PDO::FETCH_ASSOC)['total'];
    $gauge_usuarios->set($total_usuarios);

    // Contar todos os chamados
    $query_chamados = $cn->query("SELECT count(*) as total FROM chamado");
    $total_chamados = $query_chamados->fetch(PDO::FETCH_ASSOC)['total'];
    $gauge_chamados->set($total_chamados);

    // --- LÓGICA PARA AS NOVAS MÉTRICAS ---

    // 1. Contar chamados concluídos
    // Esta consulta usa a mesma lógica do nosso dashboard para encontrar o status atual
    $query_concluidos = $cn->query("
        WITH StatusAtual AS (
            SELECT
                c.id as chamado_id,
                COALESCE(hs.novo_status, c.status) as status_final
            FROM chamado c
            LEFT JOIN (
                SELECT
                    chamado_id,
                    novo_status,
                    ROW_NUMBER() OVER(PARTITION BY chamado_id ORDER BY data_alteracao DESC) as rn
                FROM historico_status
            ) hs ON c.id = hs.chamado_id AND hs.rn = 1
        )
        SELECT count(*) as total FROM StatusAtual WHERE status_final = 'Concluído'
    ");
    $total_concluidos = $query_concluidos->fetch(PDO::FETCH_ASSOC)['total'];
    $gauge_chamados_concluidos->set($total_concluidos);


    // 2. Calcular tempo médio de resolução
    // Esta consulta calcula a diferença entre a data de criação e a data da última alteração para 'Concluído'
    $query_tempo_medio = $cn->query("
        WITH ChamadosConcluidos AS (
            SELECT
                c.criado_em,
                hs.data_alteracao as data_conclusao
            FROM chamado c
            JOIN (
                SELECT
                    chamado_id,
                    data_alteracao,
                    novo_status,
                    ROW_NUMBER() OVER(PARTITION BY chamado_id ORDER BY data_alteracao DESC) as rn
                FROM historico_status
            ) hs ON c.id = hs.chamado_id
            WHERE hs.rn = 1 AND hs.novo_status = 'Concluído'
        )
        SELECT AVG(EXTRACT(EPOCH FROM (data_conclusao - criado_em))) as tempo_medio_segundos
        FROM ChamadosConcluidos
    ");
    
    $resultado_tempo_medio = $query_tempo_medio->fetch(PDO::FETCH_ASSOC);
    $tempo_medio_segundos = $resultado_tempo_medio['tempo_medio_segundos'] ?? 0;

    // MUDANÇA IMPORTANTE: Usamos os segundos diretamente!
    $gauge_tempo_medio_resolucao->set($tempo_medio_segundos);


    // --- RENDERIZAÇÃO PARA O PROMETHEUS ---
    $renderer = new RenderTextFormat();
    $result = $renderer->render($registry->getMetricFamilySamples());

    header('Content-type: ' . RenderTextFormat::MIME_TYPE);
    echo $result;

} catch (Exception $e) {
    http_response_code(500 );
    echo "Erro ao gerar métricas: " . $e->getMessage();
}
?>
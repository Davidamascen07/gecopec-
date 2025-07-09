<?php
require_once 'config/config.php';
require_once 'lib/Database.php';

echo "<h2>Debug do Sistema de Login</h2>";

// Testar conexão com banco
try {
    $db = Database::getInstance();
    echo "✅ Conexão com banco: OK<br>";
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
    exit;
}

// Verificar se usuário admin existe
try {
    $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = 'admin@gecopec.com'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "✅ Usuário admin encontrado:<br>";
        echo "ID: " . $usuario['id'] . "<br>";
        echo "Nome: " . $usuario['nome'] . "<br>";
        echo "Email: " . $usuario['email'] . "<br>";
        echo "Tipo: " . $usuario['tipo'] . "<br>";
        echo "Hash atual: " . substr($usuario['senha'], 0, 20) . "...<br><br>";
    } else {
        echo "❌ Usuário admin não encontrado!<br>";
        
        // Criar usuário admin
        echo "Criando usuário admin...<br>";
        $senha = 'adm123';
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $sqlInsert = "INSERT INTO usuarios (nome, email, senha, tipo, status) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $db->prepare($sqlInsert);
        $result = $stmtInsert->execute(['Administrador', 'admin@gecopec.com', $hash, 'admin', 'ativo']);
        
        if ($result) {
            echo "✅ Usuário admin criado com sucesso!<br>";
        } else {
            echo "❌ Erro ao criar usuário admin<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar usuário: " . $e->getMessage() . "<br>";
}

// Gerar nova senha
$senha = 'adm123';
$novoHash = password_hash($senha, PASSWORD_DEFAULT);

echo "<h3>Atualizando senha...</h3>";
try {
    $sql = "UPDATE usuarios SET senha = ? WHERE email = 'admin@gecopec.com'";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$novoHash]);
    
    if ($result) {
        echo "✅ Senha atualizada com sucesso!<br>";
        echo "Novo hash: " . $novoHash . "<br><br>";
    } else {
        echo "❌ Erro ao atualizar senha<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}

// Testar verificação de senha
echo "<h3>Testando verificação de senha...</h3>";
$senhaTest = 'adm123';
if (password_verify($senhaTest, $novoHash)) {
    echo "✅ Verificação de senha: OK<br>";
} else {
    echo "❌ Verificação de senha: FALHOU<br>";
}

echo "<br><strong>Dados para login:</strong><br>";
echo "Email: admin@gecopec.com<br>";
echo "Senha: adm123<br><br>";

echo "<strong style='color: red;'>IMPORTANTE: Remova este arquivo após o teste!</strong>";
?>

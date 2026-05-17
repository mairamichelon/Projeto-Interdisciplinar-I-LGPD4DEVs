<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">

    <!-- Cabeçalho do painel -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 35px; flex-wrap: wrap; gap: 15px;">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                <span style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 700;">
                    <i class="fas fa-shield-alt"></i> PAINEL ADMIN
                </span>
            </div>
            <h1 style="margin: 0; font-size: 1.8rem;">Dashboard</h1>
            <p style="color: var(--text-muted); margin: 4px 0 0;">Visão geral do sistema LGPD4DEVS</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="/admin/materiais" class="btn-primary" style="font-size: 0.9rem;">
                <i class="fas fa-book"></i> Materiais
            </a>
            <a href="/admin/usuarios" class="btn-secondary" style="font-size: 0.9rem;">
                <i class="fas fa-users"></i> Usuários
            </a>
        </div>
    </div>

    <!-- Cards de estatísticas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 40px;">

        <div class="card-material" style="text-align: center; padding: 25px; border-top: 4px solid var(--primary);">
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);"><?php echo $stats['total_usuarios']; ?></div>
            <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-users"></i> Usuários</div>
        </div>

        <div class="card-material" style="text-align: center; padding: 25px; border-top: 4px solid #764ba2;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #764ba2;"><?php echo $stats['total_admins']; ?></div>
            <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-shield-alt"></i> Admins</div>
        </div>

        <div class="card-material" style="text-align: center; padding: 25px; border-top: 4px solid var(--secondary);">
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--secondary);"><?php echo $stats['total_projetos']; ?></div>
            <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-folder"></i> Projetos</div>
        </div>

        <div class="card-material" style="text-align: center; padding: 25px; border-top: 4px solid #F59E0B;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #F59E0B;"><?php echo $stats['total_diagnosticos']; ?></div>
            <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-clipboard-check"></i> Diagnósticos</div>
        </div>

        <div class="card-material" style="text-align: center; padding: 25px; border-top: 4px solid #06B6D4;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #06B6D4;"><?php echo $stats['total_materiais']; ?></div>
            <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-book"></i> Materiais</div>
        </div>

        <div class="card-material" style="text-align: center; padding: 25px; border-top: 4px solid <?php echo ($stats['media_conformidade'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
            <div style="font-size: 2.5rem; font-weight: 700; color: <?php echo ($stats['media_conformidade'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                <?php echo $stats['media_conformidade']; ?>%
            </div>
            <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;"><i class="fas fa-chart-line"></i> Média Geral</div>
        </div>

    </div>

    <!-- Duas colunas: últimos usuários e últimos diagnósticos -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; flex-wrap: wrap;">

        <!-- Últimos usuários -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0;">Últimos Cadastros</h3>
                <a href="/admin/usuarios" style="font-size: 0.85rem; color: var(--primary); text-decoration: none;">Ver todos →</a>
            </div>
            <?php foreach ($ultimosUsuarios as $u): ?>
                <div class="card-pergunta" style="padding: 15px 20px; margin-bottom: 10px; border-left: 4px solid <?php echo $u['perfil'] === 'admin' ? '#764ba2' : 'var(--primary)'; ?>;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">
                                <?php echo htmlspecialchars($u['nome']); ?>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                <?php echo htmlspecialchars($u['email']); ?>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="background: <?php echo $u['perfil'] === 'admin' ? '#764ba222' : '#EFF6FF'; ?>; color: <?php echo $u['perfil'] === 'admin' ? '#764ba2' : 'var(--primary)'; ?>; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                                <?php echo $u['perfil'] === 'admin' ? '⚙️ Admin' : '👤 Usuário'; ?>
                            </span>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                                <?php echo date('d/m/Y', strtotime($u['data_cadastro'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Últimos diagnósticos -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0;">Últimos Diagnósticos</h3>
            </div>
            <?php foreach ($ultimosDiagnosticos as $d): ?>
                <div class="card-pergunta" style="padding: 15px 20px; margin-bottom: 10px; border-left: 4px solid <?php echo ($d['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">
                                <?php echo htmlspecialchars($d['usuario_nome']); ?>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                <?php echo $d['projeto_nome'] ? htmlspecialchars($d['projeto_nome']) : 'Sem projeto vinculado'; ?>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 1.3rem; font-weight: 700; color: <?php echo ($d['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                                <?php echo $d['percentual']; ?>%
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                <?php echo date('d/m/Y', strtotime($d['data_salvo'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
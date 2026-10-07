<div class="dropdown">
                    <button class="btn profile-dropdown-btn d-flex align-items-center gap-2 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="text-start d-none d-sm-block" style="line-height: 1.1;">
                            <span class="d-block fw-semibold text-dark" style="font-size: 0.85rem;"><?php echo htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'Usuario'). ' ' . htmlspecialchars($_SESSION['usuario']['apellido'] ?? ''); ?></span>
                            <span class="text-muted" style="font-size: 0.75rem;"><?php echo htmlspecialchars($_SESSION['usuario']['nombre_rol'] ?? 'Rol no asignado'); ?></span>
                        </div>
                        <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 10px;">
                        <li><a class="dropdown-menu-item dropdown-item py-2" href="?url=Usuario"><i class="bi bi-person me-2 text-purple"></i>Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><form method="post"><button type='submit' class="dropdown-item py-2 text-danger" name="logout"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</button></form></li>
                    </ul>
                </div>
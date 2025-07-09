<div class="menu">
    <div class="menu-header"><span class="menu-text">Navegación: {{ Request::route()->getname() }}</span></div>
    <div class="menu-item {{ Request::route()->getname() == 'dashboard' ? 'active' : '' }}">
        <a href="/" class="menu-link">
            <span class="menu-icon"><i class="mdi mdi-view-dashboard-variant-outline"></i></span>
            <span class="menu-text">Inicio</span>
        </a>
    </div>
    @hasallroles('firma_electronica')
        <div class="menu-item has-sub {{ substr(Request::route()->getname(), 0, 12) == 'Configuracion' ? 'active' : '' }}">
            <a href="" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                <span class="menu-text">Configuración de Firma</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu ">
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 17) == 'Configura.configura' ? 'active' : '' }}">
                    <a href="/catalogos/general/datospersonales" class="menu-link"><span class="menu-text">Configura
                            E-FIRMA</span></a>
                </div>
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 19) == 'catalogos.planteles' ? 'active' : '' }}">
                    <a href="/catalogos/general/configura" class="menu-link"><span class="menu-text">Configura autoridad
                            educativa</span></a>
                </div>
                {{-- ANA MOLINA 09/08/2023 --}}

            </div>
        </div>
    @endhasallroles
    @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
        <div class="menu-divider"></div>
        <div class="menu-header"><span class="menu-text">Ajustes</span></div>
        <div class="menu-item has-sub {{ substr(Request::route()->getname(), 0, 9) == 'catalogos' ? 'active' : '' }}">
            <a href="" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                <span class="menu-text">Catálogos</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu ">
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 19) == 'catalogos.planteles' ? 'active' : '' }}">
                    <a href="/catalogos/planteles" class="menu-link"><span class="menu-text">Planteles</span></a>
                </div>
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 19) == 'catalogos.planteles' ? 'active' : '' }}">
                    <a href="/administracion/docentes" class="menu-link"><span class="menu-text">Docentes</span></a>
                </div>
                {{-- ANA MOLINA 09/08/2023 --}}
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 25) == 'catalogos.planesdeestudio' ? 'active' : '' }}">
                    <a href="/catalogos/planesdeestudio" class="menu-link"><span class="menu-text">Planes de
                            Estudio</span></a>
                </div>
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 19) == 'catalogos.ciclosesc' ? 'active' : '' }}">
                    <a href="/catalogos/ciclosesc" class="menu-link"><span class="menu-text">Ciclos Escolares</span></a>
                </div>
                {{-- ANA MOLINA 28/06/2023 --}}
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 21) == 'catalogos.asignaturas' ? 'active' : '' }}">
                    <a href="/catalogos/asignaturas" class="menu-link"><span class="menu-text">Asignaturas</span></a>
                </div>
                {{-- ANA MOLINA 26/06/2023 --}}
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 17) == 'catalogos.nucleos' ? 'active' : '' }}">
                    <a href="/catalogos/nucleos" class="menu-link"><span class="menu-text">Núcleos</span></a>
                </div>
                {{-- ANA MOLINA 27/06/2023 --}}
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 24) == 'catalogos.areasformacion' ? 'active' : '' }}">
                    <a href="/catalogos/areasformacion" class="menu-link"><span class="menu-text">Áreas de
                            Formación</span></a>
                </div>
                {{-- ANA MOLINA 29/06/2023 --}}
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 19) == 'catalogos.politicas' ? 'active' : '' }}">
                    <a href="/catalogos/politicas" class="menu-link"><span class="menu-text">Políticas</span></a>
                </div>
                {{-- ANA MOLINA 02/08/2023 --}}
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 21) == 'catalogos.reglamentos' ? 'active' : '' }}">
                    <a href="/catalogos/reglamentos" class="menu-link"><span class="menu-text">Reglamentos</span></a>
                </div>
                {{-- ANA MOLINA 08/05/2024 --}}
                {{-- 
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 33) == 'catalogos.general.datospersonales' ? 'active' : '' }}">
                    <a href="/catalogos/general/datospersonales" class="menu-link"><span class="menu-text">Datos
                            Personales</span></a>
                </div>
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 27) == 'catalogos.general.configura' ? 'active' : '' }}">
                    <a href="/catalogos/general/configura" class="menu-link"><span class="menu-text">Configura Datos
                            Personales</span></a>
                </div>
                 --}}
            </div>
        </div>


        <div class="menu-item has-sub{{ substr(Request::route()->getname(), 0, 9) == 'adminalum' ? ' active' : '' }}">
            <a href="" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                <span class="menu-text">Administración de Alumnos</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu ">
                {{-- ANA MOLINA 23/08/2023 --}}
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.alumno' ? ' active' : '' }}">
                    <a href="/adminalumnos/alumnos" class="menu-link"><span class="menu-text">Alumnos</span></a>
                </div>
                {{--
                <div class="menu-item{{substr(Request::route()->getname(),0,19)=='adminalumnos.boleta'? ' active':''}}">
                        <a href="/adminalumnos/boleta" class="menu-link"><span class="menu-text">Boleta</span></a>
                </div>
                --}}
                @hasallroles('control_escolar')
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.kardex' ? ' active' : '' }}">
                        <a href="/adminalumnos/kardex" class="menu-link"><span class="menu-text">Kardex</span></a>
                    </div>
                @endhasallroles
                @can('traslado-alumno')
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.ingres' ? ' active' : '' }}">
                        <a href="/adminalumnos/ingresos" class="menu-link"><span class="menu-text">Revalidaciones
                                alumno</span></a>
                    </div>
                @endcan
                @can('alumno-imagen')
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.imagen' ? ' active' : '' }}">
                        <a href="{{ route('adminalumnos.imagenalumno') }}" class="menu-link"><span
                                class="menu-text">Documentación e imagen del alumno</span></a>
                    </div>
                @endcan
                @can('cursos-omitidos-alumno')
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.cursos' ? ' active' : '' }}">
                        <a href="{{ route('adminalumnos.cursos_omitidos') }}" class="menu-link"><span
                                class="menu-text">Materias Sueltas alumno</span></a>
                    </div>
                @endcan
                @can('acta_extemporanea_alumno')
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.actas_' ? ' active' : '' }}">
                        <a href="{{ route('adminalumnos.actas_ext.busqueda_plantel') }}" class="menu-link"><span
                                class="menu-text">Actas Extemporaneas</span></a>
                    </div>
                @endcan
                @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.equivalencia_' ? ' active' : '' }}">
                        <a href="{{ route('adminalumnos.equivalencia.index') }}" class="menu-link"><span
                                class="menu-text">Equivalencia y Revalidación alumno</span></a>
                    </div>
                @endif

                @can('reconocimiento_capacitacion')
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.recono' ? ' active' : '' }}">
                        <a href="{{ route('adminalumnos.reconocimiento_capacitacion.index') }}" class="menu-link"><span
                                class="menu-text">Impresión de Reconocimientos de Capacitación</span></a>
                    </div>
                @endcan
                {{--
                @hasallroles('control_escolar')
                <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.recono' ? ' active' : '' }}">
                        <a href="{{ route('adminalumnos.cambio_plan') }}" class="menu-link"><span
                                class="menu-text">Cambio de plan de estudio</span></a>
                    </div>
                @endhasallroles
                 --}}
                {{--
                @can('nuevos_alumnos')
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 27) == 'adminalumnos.nuevos_alumnos' ? ' active' : '' }}">
                        <a href="{{ route('adminalumnos.nuevos_alumnos') }}" class="menu-link"><span
                                class="menu-text">Nuevos alumnos</span></a>
                    </div>
                @endcan
                 --}}

            </div>


        </div>
        @hasrole(['super_admin', 'control_escolar'])
            <div class="menu-item has-sub{{ substr(Request::route()->getname(), 0, 9) == 'certifica' ? ' active' : '' }}">
                <a href="" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                    <span class="menu-text">Certificados</span>
                    <span class="menu-caret"><b class="caret"></b></span>
                </a>
                <div class="menu-submenu ">
                    {{-- ANA MOLINA 08/03/2024 --}}
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'certificados.genera' ? ' active' : '' }}">
                        <a href="/certificados/genera" class="menu-link"><span class="menu-text">Generación</span></a>
                    </div>
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'certificados.revisa' ? ' active' : '' }}">
                        <a href="/certificados/revisa" class="menu-link"><span class="menu-text">Revisión</span></a>
                    </div>

                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 24) == 'certificados.certificado' ? ' active' : '' }}">
                        <a href="/certificados/certificado" class="menu-link"><span class="menu-text">Documento
                                Digital</span></a>
                    </div>

                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 20) == 'certificados.cancela' ? ' active' : '' }}">
                        <a href="/certificados/cancela" class="menu-link"><span class="menu-text">Cancelación</span></a>
                    </div>
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 22) == 'certificados.visualiza' ? ' active' : '' }}">
                        <a href="/certificados/visualiza" class="menu-link"><span class="menu-text">Visor de
                                Certificados</span></a>
                    </div>
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'certificados.valida' ? ' active' : '' }}">
                        <a href="/certificados/valida" class="menu-link"><span class="menu-text">Validación de
                                Certificados</span></a>
                    </div>
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'certificados.valida' ? ' active' : '' }}">
                        <a href="/certificados/cambiociclos" class="menu-link"><span class="menu-text">Cambio de ciclos
                                certificado</span></a>
                    </div>
                    @can('sibal')
                        <div
                            class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'certificados.valida' ? ' active' : '' }}">
                            <a href="/certificados/sibal" class="menu-link"><span class="menu-text">Certificados SIBAL
                                </span></a>
                        </div>
                    @endcan

                </div>
            </div>
        @endhasrole
        <div
            class="menu-item has-sub{{ substr(Request::route()->getname(), 0, 6) == 'cursos' || substr(Request::route()->getname(), 0, 6) == 'Grupos' ? ' active' : '' }}">
            <a href="" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                <span class="menu-text">Grupos y Cursos</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu ">
                {{-- Adrian Torres 05/10/2023 --}}
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 18) == 'Grupos.crear.index' ? 'active' : '' }}">
                    <a href="/grupos/grupos" class="menu-link"><span class="menu-text">Grupos</span></a>
                </div>
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'cursos.consulta_cur' ? ' active' : '' }}">
                    <a href="/cursos/consulta" class="menu-link"><span class="menu-text">Mantenimiento
                            Cursos</span></a>
                </div>
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 20) == 'cursos.actualizar' ? ' active' : '' }}">
                    <a href="/cursos/actualizar" class="menu-link"><span class="menu-text">Actualización de
                            Cursos</span></a>
                </div>
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 21) == 'alumnos.promocion' ? ' active' : '' }}">
                    <a href="/alumnos/promocion" class="menu-link"><span class="menu-text">Promoción de
                            Alumnos</span></a>
                </div>
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 22) == 'cursos.actas' ? ' active' : '' }}">
                    <a href="/cursos/actas" class="menu-link"><span class="menu-text">Actas Especiales</span></a>
                </div>
            </div>
        </div>
        @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
            <div
                class="menu-item has-sub{{ substr(Request::route()->getname(), 0, 12) == 'credenciales' ? ' active' : '' }}">
                <a href="" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                    <span class="menu-text">Credenciales y correos institucionales</span>
                    <span class="menu-caret"><b class="caret"></b></span>
                </a>
                <div class="menu-submenu">

                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 26) == 'adminalumnos.nuevos_alumnos' ? ' active' : '' }}">
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSd02X5nbnFDkp6c6TNZ9sNogJYUlj5LD8FxHTidmaelCRcI2g/viewform?usp=sf_link"
                            target="_blank" class="menu-link"><span class="menu-text">Reposición de
                                credenciales</span></a>
                    </div>

                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 26) == 'adminalumnos.nuevos_alumnos' ? ' active' : '' }}">
                        <a href="https://forms.gle/WwZ2KL9fUakwX18w7" target="_blank" class="menu-link"><span
                                class="menu-text">Formulario de reseteo de contraseñas de correos</span></a>
                    </div>
                </div>
            </div>
        @endif
        {{-- Cambiar despues por una validación real --}}
        @if (1 == 2)
            @hasallroles('super_admin')
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'nuevos_alumnos' ? 'active' : '' }}">
                    <a href="/nuevos_alumnos" class="menu-link">
                        <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                        <span class="menu-text">Cargar nuevos alumnos</span>
                    </a>
                </div>
            @endhasallroles
        @endif
    @endif
    @hasallroles('docente')
        <div
            class="menu-item has-sub{{ substr(Request::route()->getname(), 0, 6) == 'cursos' || substr(Request::route()->getname(), 0, 6) == 'Docentes' ? ' active' : '' }}">
            <a href="" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                <span class="menu-text">Profesores</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu ">
                {{-- Adrian Torres 14/02/2023 --}}
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 20) == 'docentes.index' ? 'active' : '' }}">
                    <a href="/docentes/index" class="menu-link"><span class="menu-text">Captura de
                            calificaciones</span></a>
                </div>
                <div
                    class="menu-item {{ substr(Request::route()->getname(), 0, 20) == 'docentes.actas' ? 'active' : '' }}">
                    <a href="/docentes/actas" class="menu-link"><span class="menu-text">Visualización de actas
                            especiales</span></a>
                </div>
            </div>
        </div>
    @endhasallroles
    @hasrole(['orientacion_educativa', 'inclusion_educativa', 'tutoria_grupal', 'autorizar_rev'])
        <div class="menu-item has-sub{{ substr(Request::route()->getname(), 0, 9) == 'adminalum' ? ' active' : '' }}">
            <a href="" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                <span class="menu-text">Administración de Alumnos</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu ">
                {{-- ANA MOLINA 23/08/2023 --}}
                @hasrole(['orientacion_educativa', 'inclusion_educativa', 'tutoria_grupal'])
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.alumno' ? ' active' : '' }}">
                        <a href="/adminalumnos/alumnos" class="menu-link"><span class="menu-text">Alumnos</span></a>
                    </div>

                @endhasallroles
                @if (Auth::user()->roles->pluck('name')->contains(function ($role) {
                            return strlen($role) === 3;
                        }))
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'cursos.consulta_cur' ? ' active' : '' }}">
                        <a href="/cursos/consulta" class="menu-link"><span class="menu-text">Mantenimiento
                                Cursos</span></a>
                    </div>
                @endif
                @hasrole(['super_admin', 'control_escolar', 'autorizar_rev'])
                    <div
                        class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'adminalumnos.equivalencia_' ? ' active' : '' }}">
                        <a href="{{ route('adminalumnos.equivalencia.index') }}" class="menu-link"><span
                                class="menu-text">Equivalencia y Revalidación alumno</span></a>
                    </div>
                    {{-- 
            @endif --}}
                @endhasallroles
            </div>


        </div>
    @endhasrole {{-- ANA MOLINA 19/02/2024 --}}

    {{-- @hasallroles('super_admin')
        <div
            class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'estadisticas.calificaciones' ? ' active' : '' }}">
            <a href="/estadisticas/calificaciones" class="menu-link"><span class="menu-text">Calificaciones</span></a>
        </div>
    @endhasallroles
 --}}
    @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
        <div
            class="menu-item has-sub{{ substr(Request::route()->getname(), 0, 9) == 'estadisticas' ? ' active' : '' }}">
            <a href="" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-database-outline"></i></span>
                <span class="menu-text">Estadísticas</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu ">
                {{-- ANA MOLINA 23/08/2023
                
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'estadisticas.matricula' ? ' active' : '' }}">
                    <a href="/estadisticas/matricula" class="menu-link"><span class="menu-text">Matrícula
                            escolar</span></a>
                </div>
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'estadisticas.grupos' ? ' active' : '' }}">
                    <a href="/estadisticas/grupos" class="menu-link"><span class="menu-text">Grupos</span></a>
                </div>

                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 19) == 'estadisticas.calificaciones' ? ' active' : '' }}">
                    <a href="/estadisticas/calificaciones" class="menu-link"><span
                            class="menu-text">Calificaciones</span></a>
                </div>
                --}}
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 20) == 'estadisticas.tablero' ? ' active' : '' }}">
                    <a href="/estadisticas/tablero" class="menu-link"><span class="menu-text">Tablero promedios
                            alumnos aprobados/reprobados</span></a>
                </div>
                <div
                    class="menu-item{{ substr(Request::route()->getname(), 0, 21) == 'estadisticas.explorar' ? ' active' : '' }}">
                    <a href="/estadisticas/explorar" class="menu-link"><span class="menu-text">Explorar
                            Datos</span></a>
                </div>

            </div>

        </div>
    @endif

    @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
        <div class="menu-divider"></div>
        <div class="menu-header"><span class="menu-text">Administración</span></div>
        <div
            class="menu-item has-sub {{ substr(Request::route()->getname(), 0, 4) == 'user' ? 'active' : '' }}
                                      {{ substr(Request::route()->getname(), 0, 3) == 'rol' ? 'active' : '' }}">
            <a href="" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-account-group"></i></span>
                <span class="menu-text">Acceso</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu">
                <div class="menu-item {{ substr(Request::route()->getname(), 0, 4) == 'user' ? 'active' : '' }}">
                    <a href="/usuarios" class="menu-link"><span class="menu-text">Usuarios</span></a>
                </div>
                <div class="menu-item {{ substr(Request::route()->getname(), 0, 3) == 'rol' ? 'active' : '' }}">
                    <a href="/roles" class="menu-link"><span class="menu-text">Roles</span></a>
                </div>
                @hasallroles('super_admin')
                    <div class="menu-item {{ substr(Request::route()->getname(), 0, 5) == 'rol' ? 'active' : '' }}">
                        <a href="/apertura" class="menu-link"><span class="menu-text">Apertura Docentes</span></a>
                    </div>
                @endhasallroles
                @hasallroles('super_admin')
                    <div class="menu-item {{ substr(Request::route()->getname(), 0, 5) == 'rol' ? 'active' : '' }}">
                        <a href="/replicacion" class="menu-link"><span class="menu-text">Replica de
                                calificaciones</span></a>
                    </div>
                @endhasallroles

            </div>
        </div>

        @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar') || Auth()->user()->can('credenciales'))
            <div
                class="menu-item has-sub {{ substr(Request::route()->getname(), 0, 4) == 'user' ? 'active' : '' }} {{ substr(Request::route()->getname(), 0, 3) == 'rol' ? 'active' : '' }}">
                <a href="#" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-account-group"></i></span>
                    <span class="menu-text">Reportes</span>
                    <span class="menu-caret"><b class="caret"></b></span>
                </a>

                <div class="menu-submenu">
                    <!-- Submenú para "Reportes de Alumnos" -->
                    <div class="menu-item has-sub">
                        <a href="#" class="menu-link">
                            <span class="menu-text">Reportes de Alumnos</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">

                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/catalogo_alumnos" class="menu-link">
                                    <span class="menu-text">Catálogo de alumnos con fotos</span>
                                </a>
                            </div>

                            @can('credenciales')
                                <div
                                    class="menu-item {{ substr(Request::route()->getname(), 0, 9) == 'reportes' ? 'active' : '' }}">
                                    <a href="/alumno/credencialporalumno" class="menu-link">
                                        <span class="menu-text">Identificación del alumno</span>
                                    </a>
                                </div>
                                <div
                                    class="menu-item {{ substr(Request::route()->getname(), 0, 9) == 'reportes' ? 'active' : '' }}">
                                    <a href="/reporte_plantel_fotos" class="menu-link">
                                        <span class="menu-text">Reporte de alumnos por plantel</span>
                                    </a>
                                </div>
                            @endcan


                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_alumnosreprobados" class="menu-link">
                                    <span class="menu-text">Reporte alumnos reprobados</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_alumnosenriesgo" class="menu-link">
                                    <span class="menu-text">Reporte alumnos en riesgo de reprobación</span>
                                </a>
                            </div>
                            @can('reporte_reprobadas_historica')
                                <div
                                    class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                    <a href="/reporte_asignaturas_reprobadas" class="menu-link">
                                        <span class="menu-text">Reporte de asignaturas reprobadas historicas por
                                            alumno</span>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <!-- Submenú para "Reporte de grupos" -->
                    <div class="menu-item has-sub">
                        <a href="#" class="menu-link">
                            <span class="menu-text">Reporte de grupos</span>
                            <span class="menu-caret"><b class="caret"></b></span>
                        </a>
                        <div class="menu-submenu">
                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 9) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_egreso" class="menu-link">
                                    <span class="menu-text">Egreso por plantel</span>
                                </a>
                            </div>
                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_mejorespromedios" class="menu-link">
                                    <span class="menu-text">Reporte mejores promedios parciales</span>
                                </a>
                            </div>


                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_promedio" class="menu-link">
                                    <span class="menu-text">Reporte promedio generación</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="/listas_docente" class="menu-link">
                                    <span class="menu-text">Reporte de asistencias por asignaturas</span>
                                </a>

                            </div>

                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_movimientosmensuales" class="menu-link"><span
                                        class="menu-text">Reporte
                                        movimientos mensuales</span></a>
                            </div>
                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_indicadordesercion" class="menu-link"><span
                                        class="menu-text">Reporte
                                        indicador de deserción</span></a>
                            </div>
                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_egresados" class="menu-link"><span class="menu-text">Reporte
                                        egresados</span></a>
                            </div>
                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_ingresos" class="menu-link"><span class="menu-text">Reporte
                                        ingresos</span></a>

                            </div>
                        </div>
                    </div>
                    {{-- Otros ítems del menú principal, si los hubiera, se agregarían aquí --}}
                </div>
            </div>
        @endif



        <div class="menu-item {{ substr(Request::route()->getname(), 0, 8) == 'bitacora' ? 'active' : '' }}">
            <a href="/bitacora/" class="menu-link">
                <span class="menu-icon"><i class="mdi mdi-book-clock-outline"></i></span>
                <span class="menu-text">Bitácora</span>
            </a>
        </div>
    @endif
    @if (str_contains(Auth()->user()->roles->pluck('name'), 'credenciales'))
        @can('credenciales')
            <div
                class="menu-item has-sub {{ substr(Request::route()->getname(), 0, 4) == 'user' ? 'active' : '' }}
    {{ substr(Request::route()->getname(), 0, 3) == 'rol' ? 'active' : '' }}">
                <a href="" class="menu-link">
                    <span class="menu-icon"><i class="mdi mdi-account-group"></i></span>
                    <span class="menu-text">Reportes</span>
                    <span class="menu-caret"><b class="caret"></b></span>
                </a>
                <div class="menu-submenu">
                    @if (str_contains(Auth()->user()->roles->pluck('name'), 'control_escolar'))
                        @can('credenciales')
                            <div
                                class="menu-item {{ substr(Request::route()->getname(), 0, 9) == 'reportes' ? 'active' : '' }}">
                                <a href="/reporte_plantel_fotos" class="menu-link"><span class="menu-text">Reporte de alumnos
                                        por
                                        plantel</span></a>
                            </div>
                        @endcan
                    @endif
                    @if (str_contains(Auth()->user()->roles->pluck('name'), 'credenciales'))
                        <div
                            class="menu-item {{ substr(Request::route()->getname(), 0, 9) == 'reportes' ? 'active' : '' }}">
                            <a href="/reporte_plantel_fotos_credenciales" class="menu-link"><span
                                    class="menu-text">Reporte
                                    de alumnos por
                                    plantel</span></a>
                        </div>
                    @endif

                </div>
            </div>
        @endcan
    @endif



    <div class="menu-divider"></div>
    <div class="menu-header"><span class="menu-text">Cerrar Sesión</span></div>
    <div class="menu-item  ">
        <a href="{{ route('logout') }}" class="menu-link">
            <span class="menu-icon"><i class="mdi mdi-logout-variant"></i></span>
            <span class="menu-text">Salir del Sistema</span>
        </a>
    </div>
</div>

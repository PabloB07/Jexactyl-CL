import classNames from 'classnames';
import UptimeDuration from '../UptimeDuration';
import { ServerContext } from '@/state/server';
import React, { useEffect, useState } from 'react';
import ContentBox from '@/components/elements/ContentBox';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import styles from '@/components/server/console/style.module.css';
import { SocketEvent, SocketRequest } from '@/components/server/events';
import { faClock, faMicrochip, faMemory, faHdd } from '@fortawesome/free-solid-svg-icons';

type Stats = Record<'memory' | 'cpu' | 'disk' | 'uptime', number>;

const getColorFromStatus = (status: string): string => {
    switch (status) {
        case 'offline':
            return 'text-red-500';
        case 'starting':
            return 'text-yellow-500';
        case 'stopping':
            return 'text-yellow-500';
        case 'running':
            return 'text-green-500';
        default:
            return 'text-gray-500';
    }
};

const getStatusFromUsage = (usage: number): string => {
    if (usage <= 0) return 'N/A';
    if (usage <= 25) return 'low';
    if (usage >= 80) return 'high';
    else return 'normal';
};

export default () => {
    const status = ServerContext.useStoreState((state) => state.status.value);
    const [stats, setStats] = useState<Stats>({ memory: 0, cpu: 0, disk: 0, uptime: 0 });

    const instance = ServerContext.useStoreState((state) => state.socket.instance);
    const connected = ServerContext.useStoreState((state) => state.socket.connected);
    const limits = ServerContext.useStoreState((state) => state.server.data!.limits);

    const cpuUsed = ((stats.cpu / limits.cpu) * 100).toFixed(2);
    const diskUsed = ((stats.disk / 1024 / 1024 / limits.disk) * 100).toFixed(2);
    const memoryUsed = ((stats.memory / 1024 / 1024 / limits.memory) * 100).toFixed(2);

    const statsListener = (data: string) => {
        let stats: any = {};

        try {
            stats = JSON.parse(data);
        } catch (e) {
            return;
        }

        setStats({
            memory: stats.memory_bytes,
            cpu: stats.cpu_absolute,
            disk: stats.disk_bytes,
            uptime: stats.uptime || 0,
        });
    };

    useEffect(() => {
        if (!connected || !instance) {
            return;
        }

        instance.addListener(SocketEvent.STATS, statsListener);
        instance.send(SocketRequest.SEND_STATS);

        return () => {
            instance.removeListener(SocketEvent.STATS, statsListener);
        };
    }, [instance, connected]);

    return (
        <div className={classNames(styles.chart_container, 'group')}>
            <div className={'flex items-center justify-between px-4 py-2'}>
                <h3
                    className={'font-header transition-colors duration-100 group-hover:text-gray-50 font-semibold mb-2'}
                >
                    Status atual
                </h3>
            </div>
            <div className={'grid grid-cols-2 gap-3 mx-4'}>
                <ContentBox isLight>
                    <div className={'text-center'}>
                        <p className={'font-bold text-xl'}>
                            <FontAwesomeIcon icon={faClock} size={'1x'} />
                            <br />O servidor está{' '}
                            <span className={getColorFromStatus(status ?? 'apagado')}>{status}</span>
                        </p>
                        <p className={'font-semibold text-sm text-gray-400 mt-1'}>
                            {status === 'running' ? (
                                <UptimeDuration uptime={stats.uptime / 1000} />
                            ) : (
                                <>No se puede obtener tiempo de actividad</>
                            )}
                        </p>
                    </div>
                </ContentBox>
                <ContentBox isLight>
                    <div className={'text-center'}>
                        <p className={'font-bold text-xl'}>
                            <FontAwesomeIcon icon={faMicrochip} size={'1x'} />
                            <br />
                            Utilización de {getStatusFromUsage(parseInt(cpuUsed))}
                        </p>
                        {status === 'running' ? (
                            <p className={'font-semibold text-sm text-gray-400 mt-1'}>Usando {cpuUsed}%</p>
                        ) : (
                            <p className={'font-semibold text-sm text-gray-400 mt-1'}>Servidor Apagado</p>
                        )}
                    </div>
                </ContentBox>
                <ContentBox isLight>
                    <div className={'text-center'}>
                        <p className={'font-bold text-xl'}>
                            <FontAwesomeIcon icon={faMemory} size={'1x'} />
                            <br />
                            Utilización de RAM {getStatusFromUsage(parseInt(memoryUsed))}
                        </p>
                        {status === 'running' ? (
                            <p className={'font-semibold text-sm text-gray-400 mt-1'}>Usando {memoryUsed}%</p>
                        ) : (
                            <p className={'font-semibold text-sm text-gray-400 mt-1'}>Servidor Apagado</p>
                        )}
                    </div>
                </ContentBox>
                <ContentBox isLight>
                    <div className={'text-center'}>
                        <p className={'font-bold text-xl'}>
                            <FontAwesomeIcon icon={faHdd} size={'1x'} />
                            <br />
                            Utilización de disco {getStatusFromUsage(parseInt(diskUsed))}
                        </p>
                        {status === 'running' ? (
                            <p className={'font-semibold text-sm text-gray-400 mt-1'}>Usando {diskUsed}%</p>
                        ) : (
                            <p className={'font-semibold text-sm text-gray-400 mt-1'}>Servidor Apagado</p>
                        )}
                    </div>
                </ContentBox>
            </div>
        </div>
    );
};

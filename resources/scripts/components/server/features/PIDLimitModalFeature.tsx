import tw from 'twin.macro';
import * as Icon from 'react-feather';
import useFlash from '@/plugins/useFlash';
import { useStoreState } from 'easy-peasy';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import React, { useEffect, useState } from 'react';
import { SocketEvent } from '@/components/server/events';
import { Button } from '@/components/elements/button/index';
import FlashMessageRender from '@/components/FlashMessageRender';

const PIDLimitModalFeature = () => {
    const [visible, setVisible] = useState(false);
    const [loading] = useState(false);

    const status = ServerContext.useStoreState((state) => state.status.value);
    const { clearFlashes } = useFlash();
    const { connected, instance } = ServerContext.useStoreState((state) => state.socket);
    const isAdmin = useStoreState((state) => state.user.data!.rootAdmin);

    useEffect(() => {
        if (!connected || !instance || status === 'running') return;

        const errors = [
            'pthread_create falló',
            'falló la creacion del thread',
            'incapaz de crear el thread',
            'incapaz de crear el thread nativo',
            'incapaz de crear nuevos thread nativos',
            'excepción en el hilo "proceso de gestión del programador asíncrono artesanal"',
        ];

        const listener = (line: string) => {
            if (errors.some((p) => line.toLowerCase().includes(p))) {
                setVisible(true);
            }
        };

        instance.addListener(SocketEvent.CONSOLE_OUTPUT, listener);

        return () => {
            instance.removeListener(SocketEvent.CONSOLE_OUTPUT, listener);
        };
    }, [connected, instance, status]);

    useEffect(() => {
        clearFlashes('feature:pidLimit');
    }, []);

    return (
        <Modal
            visible={visible}
            onDismissed={() => setVisible(false)}
            closeOnBackground={false}
            showSpinnerOverlay={loading}
        >
            <FlashMessageRender key={'feature:pidLimit'} css={tw`mb-4`} />
            {isAdmin ? (
                <>
                    <div css={tw`mt-4 sm:flex items-center`}>
                        <Icon.AlertTriangle css={tw`pr-4`} color={'orange'} size={'4x'} />
                        <h2 css={tw`text-2xl mb-4 text-neutral-100 `}>Se alcanzó el límite de memoria o proceso...</h2>
                    </div>
                    <p css={tw`mt-4`}>
                        Este servidor ha alcanzado el límite máximo de procesador o memoria..</p>
                    <p css={tw`mt-4`}>
                        Aumentar <code css={tw`font-mono bg-neutral-900`}>container_pid_limit</code> en la configuración
                        de Wings, <code css={tw`font-mono bg-neutral-900`}>config.yml</code>, puede ayudar a resolver
                        Ese asunto.
                    </p>
                    <p css={tw`mt-4`}>
                        <b>
                            Nota: Se debe iniciar Wings para que los cambios en el archivo de configuración surtan efecto.
                        </b>
                    </p>
                    <div css={tw`mt-8 sm:flex items-center justify-end`}>
                        <Button onClick={() => setVisible(false)} css={tw`w-full sm:w-auto border-transparent`}>
                            Cerrar
                        </Button>
                    </div>
                </>
            ) : (
                <>
                    <div css={tw`mt-4 sm:flex items-center`}>
                        <Icon.AlertTriangle css={tw`pr-4`} color={'orange'} size={'4x'} />
                        <h2 css={tw`text-2xl mb-4 text-neutral-100`}>Posible límite de recursos alcanzado...</h2>
                    </div>
                    <p css={tw`mt-4`}>
                        Este servidor está intentando utilizar más recursos de los asignados. Ponte en contacto con el
                        administrador y darles el siguiente error.
                    </p>
                    <p css={tw`mt-4`}>
                        <code css={tw`font-mono bg-neutral-900`}>
                            pthread_create falló, posiblemente sin memoria o se alcanzaron los límites de proceso/recursos
                        </code>
                    </p>
                    <div css={tw`mt-8 sm:flex items-center justify-end`}>
                        <Button onClick={() => setVisible(false)} css={tw`w-full sm:w-auto border-transparent`}>
                            Cerrar
                        </Button>
                    </div>
                </>
            )}
        </Modal>
    );
};

export default PIDLimitModalFeature;

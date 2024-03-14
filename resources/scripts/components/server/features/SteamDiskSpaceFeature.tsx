import tw from 'twin.macro';
import useFlash from '@/plugins/useFlash';
import { useStoreState } from 'easy-peasy';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import React, { useEffect, useState } from 'react';
import { SocketEvent } from '@/components/server/events';
import { Button } from '@/components/elements/button/index';
import FlashMessageRender from '@/components/FlashMessageRender';

const SteamDiskSpaceFeature = () => {
    const [visible, setVisible] = useState(false);
    const [loading] = useState(false);

    const status = ServerContext.useStoreState((state) => state.status.value);
    const { clearFlashes } = useFlash();
    const { connected, instance } = ServerContext.useStoreState((state) => state.socket);
    const isAdmin = useStoreState((state) => state.user.data!.rootAdmin);

    useEffect(() => {
        if (!connected || !instance || status === 'running') return;

        const errors = [
            'Steamcmd necesita 250 MB de espacio libre en disco para actualizarse',
            '0x202 después del trabajo de actualización',
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
        clearFlashes('feature:steamDiskSpace');
    }, []);

    return (
        <Modal
            visible={visible}
            onDismissed={() => setVisible(false)}
            closeOnBackground={false}
            showSpinnerOverlay={loading}
        >
            <FlashMessageRender key={'feature:steamDiskSpace'} css={tw`mb-4`} />
            {isAdmin ? (
                <>
                    <div css={tw`mt-4 sm:flex items-center`}>
                        <h2 css={tw`text-2xl mb-4 text-neutral-100 `}>Sin espacio disponible en el disco..</h2>
                    </div>
                    <p css={tw`mt-4`}>
                        Este servidor se ha quedado sin espacio disponible en disco y no puede completar el proceso de instalación.
                        o actualizar.
                    </p>
                    <p css={tw`mt-4`}>
                        Asegúrese de que su máquina tenga suficiente espacio en disco al escribir {' '}
                        <code css={tw`font-mono bg-neutral-900 rounded py-1 px-2`}>df -h</code> en el alojamiento de la máquina
                        Este servidor. Elimine archivos o aumente el espacio disponible en disco para resolver el problema
                        problema.
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
                        <h2 css={tw`text-2xl mb-4 text-neutral-100`}>Sin espacio disponible en el disco...</h2>
                    </div>
                    <p css={tw`mt-4`}>
                        Este servidor se ha quedado sin espacio disponible en disco y no puede completar la instalación o
                        actualización del proceso. Por favor, póngase en contacto con los administradores e infórmeles.
                        sobre problemas de espacio en disco.
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

export default SteamDiskSpaceFeature;

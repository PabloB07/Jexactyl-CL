import tw from 'twin.macro';
import useFlash from '@/plugins/useFlash';
import Can from '@/components/elements/Can';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import Select from '@/components/elements/Select';
import React, { useEffect, useState } from 'react';
import getServerStartup from '@/api/swr/getServerStartup';
import { Button } from '@/components/elements/button/index';
import useWebsocketEvent from '@/plugins/useWebsocketEvent';
import InputSpinner from '@/components/elements/InputSpinner';
import FlashMessageRender from '@/components/FlashMessageRender';
import { SocketEvent, SocketRequest } from '@/components/server/events';
import setSelectedDockerImage from '@/api/server/setSelectedDockerImage';

const MATCH_ERRORS = [
    'unsupported major.minor version',
    'java.lang.unsupportedclassversionerror',
    'fue compilado por una versión más nueva del tiempo de ejecución de Java',
    'minecraft 1.17 Requiere ejecutar el servidor con Java 16 o arriba',
    'minecraft 1.18 Requiere ejecutar el servidor con Java 17 o arriba',
];

const JavaVersionModalFeature = () => {
    const [visible, setVisible] = useState(false);
    const [loading, setLoading] = useState(false);
    const [selectedVersion, setSelectedVersion] = useState('');

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const status = ServerContext.useStoreState((state) => state.status.value);
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { instance } = ServerContext.useStoreState((state) => state.socket);

    const { data, isValidating, mutate } = getServerStartup(uuid, null, {
        revalidateOnMount: false,
    });

    useEffect(() => {
        if (!visible) return;

        mutate().then((value) => {
            setSelectedVersion(Object.values(value?.dockerImages || [])[0] || '');
        });
    }, [visible]);

    useWebsocketEvent(SocketEvent.CONSOLE_OUTPUT, (data) => {
        if (status === 'running') return;

        if (MATCH_ERRORS.some((p) => data.toLowerCase().includes(p.toLowerCase()))) {
            setVisible(true);
        }
    });

    const updateJava = () => {
        setLoading(true);
        clearFlashes('feature:javaVersion');

        setSelectedDockerImage(uuid, selectedVersion)
            .then(() => {
                if (status === 'offline' && instance) {
                    instance.send(SocketRequest.SET_STATE, 'restart');
                }
                setVisible(false);
            })
            .catch((error) => clearAndAddHttpError({ key: 'feature:javaVersion', error }))
            .then(() => setLoading(false));
    };

    useEffect(() => {
        clearFlashes('feature:javaVersion');
    }, []);

    return (
        <Modal
            visible={visible}
            onDismissed={() => setVisible(false)}
            closeOnBackground={false}
            showSpinnerOverlay={loading}
        >
            <FlashMessageRender key={'feature:javaVersion'} css={tw`mb-4`} />
            <h2 css={tw`text-2xl mb-4 text-neutral-100`}>Versão Java não suportada</h2>
            <p css={tw`mt-4`}>
                Este servidor ejecuta actualmente una versión no compatible de Java y no se puede iniciar.
                <Can action={'startup.docker-image'}>
                    &nbsp;Seleccione una versión compatible de la lista siguiente para continuar iniciando el servidor.
                </Can>
            </p>
            <Can action={'startup.docker-image'}>
                <div css={tw`mt-4`}>
                    <InputSpinner visible={!data || isValidating}>
                        <Select disabled={!data} onChange={(e) => setSelectedVersion(e.target.value)}>
                            {!data ? (
                                <option disabled />
                            ) : (
                                Object.keys(data.dockerImages).map((key) => (
                                    <option key={key} value={data.dockerImages[key]}>
                                        {key}
                                    </option>
                                ))
                            )}
                        </Select>
                    </InputSpinner>
                </div>
            </Can>
            <div css={tw`mt-8 flex flex-col sm:flex-row justify-end sm:space-x-4 space-y-4 sm:space-y-0`}>
                <Button
                    variant={Button.Variants.Secondary}
                    onClick={() => setVisible(false)}
                    css={tw`w-full sm:w-auto`}
                >
                    Cancelar
                </Button>
                <Can action={'startup.docker-image'}>
                    <Button onClick={updateJava} css={tw`w-full sm:w-auto`}>
                        Actualize la imagen de Docker
                    </Button>
                </Can>
            </div>
        </Modal>
    );
};

export default JavaVersionModalFeature;

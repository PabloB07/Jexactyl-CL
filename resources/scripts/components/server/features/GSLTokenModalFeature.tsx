import tw from 'twin.macro';
import { Form, Formik } from 'formik';
import useFlash from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import Field from '@/components/elements/Field';
import React, { useEffect, useState } from 'react';
import { Button } from '@/components/elements/button/index';
import FlashMessageRender from '@/components/FlashMessageRender';
import updateStartupVariable from '@/api/server/updateStartupVariable';
import { SocketEvent, SocketRequest } from '@/components/server/events';

interface Values {
    gslToken: string;
}

const GSLTokenModalFeature = () => {
    const [visible, setVisible] = useState(false);
    const [loading, setLoading] = useState(false);

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const status = ServerContext.useStoreState((state) => state.status.value);
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { connected, instance } = ServerContext.useStoreState((state) => state.socket);

    useEffect(() => {
        if (!connected || !instance || status === 'running') return;

        const errors = ['(gsl token expired)', '(account not found)'];

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

    const updateGSLToken = (values: Values) => {
        setLoading(true);
        clearFlashes('feature:gslToken');

        updateStartupVariable(uuid, 'STEAM_ACC', values.gslToken)
            .then(() => {
                if (instance) {
                    instance.send(SocketRequest.SET_STATE, 'restart');
                }

                setLoading(false);
                setVisible(false);
            })
            .catch((error) => {
                console.error(error);
                clearAndAddHttpError({ key: 'feature:gslToken', error });
            })
            .then(() => setLoading(false));
    };

    useEffect(() => {
        clearFlashes('feature:gslToken');
    }, []);

    return (
        <Formik onSubmit={updateGSLToken} initialValues={{ gslToken: '' }}>
            <Modal
                visible={visible}
                onDismissed={() => setVisible(false)}
                closeOnBackground={false}
                showSpinnerOverlay={loading}
            >
                <FlashMessageRender key={'feature:gslToken'} css={tw`mb-4`} />
                <Form>
                    <h2 css={tw`text-2xl mb-4 text-neutral-100`}>Token GSL inválido!</h2>
                    <p css={tw`mt-4`}>
                        Parece que su token de inicio de sesión del servidor de juegos (token TLS) no es válido o ha caducado.
                    </p>
                    <p css={tw`mt-4`}>
                        Puedes generar uno nuevo e ingresarlo a continuación o dejar el campo en blanco para eliminarlo.
                        completamente.
                    </p>
                    <div css={tw`sm:flex items-center mt-4`}>
                        <Field
                            name={'gslToken'}
                            label={'GSL Token'}
                            description={'Visite https://steamcommunity.com/dev/managegameservers para generar un token.'}
                            autoFocus
                        />
                    </div>
                    <div css={tw`mt-8 sm:flex items-center justify-end`}>
                        <Button type={'submit'} css={tw`mt-4 sm:mt-0 sm:ml-4 w-full sm:w-auto`}>
                            Actualize el token GSL
                        </Button>
                    </div>
                </Form>
            </Modal>
        </Formik>
    );
};

export default GSLTokenModalFeature;

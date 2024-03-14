import React, { useState } from 'react';
import useFlash from '@/plugins/useFlash';
import Code from '@/components/elements/Code';
import { ServerContext } from '@/state/server';
import Input from '@/components/elements/Input';
import deleteServer from '@/api/server/deleteServer';
import { Dialog } from '@/components/elements/dialog';
import { Button } from '@/components/elements/button/index';
import TitledGreyBox from '@/components/elements/TitledGreyBox';

export default () => {
    const [name, setName] = useState('');
    const [warn, setWarn] = useState(false);
    const [confirm, setConfirm] = useState(false);

    const { addFlash, clearFlashes, clearAndAddHttpError } = useFlash();

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const serverName = ServerContext.useStoreState((state) => state.server.data!.name);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        e.stopPropagation();
        clearFlashes('settings');

        deleteServer(uuid, name)
            .then(() => {
                setConfirm(false);
                addFlash({
                    key: 'settings',
                    type: 'success',
                    message: 'Tu servidor fue excluido',
                });
                // @ts-expect-error this is valid
                window.location = '/';
            })
            .catch((error) => clearAndAddHttpError({ key: 'settings', error }));
    };

    return (
        <TitledGreyBox title={'Excluir servidor'} className={'relative mb-12'}>
            <Dialog.Confirm
                open={warn}
                title={'Confirme la exclusión del servidor'}
                confirm={'Si, excluir el servidor'}
                onClose={() => setWarn(false)}
                onConfirmed={() => {
                    setConfirm(true);
                    setWarn(false);
                }}
            >
                Se eliminará su servidor, se eliminarán todos los archivos y se eliminarán los recursos del servidor.
                regresado a tu cuenta. ¿Estás seguro de que deseas continuar?
            </Dialog.Confirm>
            <form id={'delete-server-form'} onSubmit={submit}>
                <Dialog
                    open={confirm}
                    title={'Confirm server deletion'}
                    onClose={() => {
                        setConfirm(false);
                        setName('');
                    }}
                >
                    {name !== serverName && (
                        <>
                            <p className={'my-2 text-gray-400'}>
                                Modelo <Code>{serverName}</Code> abaixo de.
                            </p>
                            <Input type={'text'} value={name} onChange={(n) => setName(n.target.value)} />
                        </>
                    )}
                    <Button
                        disabled={name !== serverName}
                        type={'submit'}
                        className={'mt-2'}
                        form={'delete-server-form'}
                    >
                        Sim, exclua o servidor
                    </Button>
                </Dialog>
            </form>
            <p className={'text-sm'}>
                Eliminar su servidor cerrará cualquier proceso, devolverá recursos a su cuenta y eliminará todos
                los archivos asociados con la instancia, así como las copias de seguridad, las bases de datos y las configuraciones.{' '}
                <strong className={'font-medium'}>
                    Todos los datos se perderán permanentemente si continúa con esta acción.
                </strong>
            </p>
            <div className={'mt-6 font-medium text-right'}>
                <Button.Danger variant={Button.Variants.Secondary} onClick={() => setWarn(true)}>
                    Excluir servidor
                </Button.Danger>
            </div>
        </TitledGreyBox>
    );
};

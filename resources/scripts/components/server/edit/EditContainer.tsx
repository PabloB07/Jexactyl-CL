import tw from 'twin.macro';
import { breakpoint } from '@/theme';
import * as Icon from 'react-feather';
import React, { useState } from 'react';
import useFlash from '@/plugins/useFlash';
import styled from 'styled-components/macro';
import { ServerContext } from '@/state/server';
import editServer from '@/api/server/editServer';
import { Dialog } from '@/components/elements/dialog';
import { Button } from '@/components/elements/button/index';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import ServerContentBlock from '@/components/elements/ServerContentBlock';

const Container = styled.div`
    ${tw`flex flex-wrap`};

    & > div {
        ${tw`w-full`};

        ${breakpoint('sm')`
      width: calc(50% - 1rem);
    `}

        ${breakpoint('md')`
      ${tw`w-auto flex-1`};
    `}
    }
`;

const Wrapper = styled.div`
    ${tw`text-2xl flex flex-row justify-center items-center`};
`;

export default () => {
    const [submitting, setSubmitting] = useState(false);
    const [resource, setResource] = useState('');
    const [amount, setAmount] = useState(0);

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { clearFlashes, addFlash, clearAndAddHttpError } = useFlash();

    const edit = (resource: string, amount: number) => {
        clearFlashes('server:edit');
        setSubmitting(true);

        editServer(uuid, resource, amount)
            .then(() => {
                setSubmitting(false);
                addFlash({
                    key: 'server:edit',
                    type: 'success',
                    message: 'Los recursos del servidor fueron editados con éxito.',
                });
            })
            .catch((error) => clearAndAddHttpError({ key: 'server:edit', error }));
    };

    return (
        <ServerContentBlock
            title={'Editar Servidor'}
            description={'Adicionnar o remover recursos.'}
            showFlashKey={'server:edit'}
        >
            <SpinnerOverlay size={'large'} visible={submitting} />
            <Dialog.Confirm
                open={submitting}
                onClose={() => setSubmitting(false)}
                title={'Confirme la edición de recursos'}
                onConfirmed={() => edit(resource, amount)}
            >
                Esto transferirá recursos entre su cuenta y el servidor. Estás seguro de que quieres continuar?
            </Dialog.Confirm>
            <Container css={tw`lg:grid lg:grid-cols-3 gap-4 my-10`}>
                <TitledGreyBox title={'Editar limite de CPU del servidor'} css={tw`mt-8 sm:mt-0`}>
                    <Wrapper>
                        <Icon.Cpu size={40} />
                        <Button.Success
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('cpu');
                                setAmount(50);
                            }}
                        >
                            <Icon.Plus />
                        </Button.Success>
                        <Button.Danger
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('cpu');
                                setAmount(-50);
                            }}
                        >
                            <Icon.Minus />
                        </Button.Danger>
                    </Wrapper>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        Cambie la cantidad de CPU asignada al servidor.
                    </p>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        El límite no puede ser inferior al 50%.
                    </p>
                </TitledGreyBox>
                <TitledGreyBox title={'Editar Limite de RAM del servidor'} css={tw`mt-8 sm:mt-0 sm:ml-8`}>
                    <Wrapper>
                        <Icon.PieChart size={40} />
                        <Button.Success
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('memory');
                                setAmount(1024);
                            }}
                        >
                            <Icon.Plus />
                        </Button.Success>
                        <Button.Danger
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('memory');
                                setAmount(-1024);
                            }}
                        >
                            <Icon.Minus />
                        </Button.Danger>
                    </Wrapper>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        Cambie la cantidad de RAM asignada al servidor.
                    </p>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        Cambie la cantidad de RAM asignada al servidor.
                    </p>
                </TitledGreyBox>
                <TitledGreyBox title={'Editar límite de almacenamiento del servidor'} css={tw`mt-8 sm:mt-0 sm:ml-8`}>
                    <Wrapper>
                        <Icon.HardDrive size={40} />
                        <Button.Success
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('disk');
                                setAmount(1024);
                            }}
                        >
                            <Icon.Plus />
                        </Button.Success>
                        <Button.Danger
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('disk');
                                setAmount(-1024);
                            }}
                        >
                            <Icon.Minus />
                        </Button.Danger>
                    </Wrapper>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        Cambie la cantidad de almacenamiento asignado al servidor.
                    </p>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        El límite no puede ser inferior a 1 GB.
                    </p>
                </TitledGreyBox>
                <TitledGreyBox title={'Editar número de puertos del servidor'} css={tw`mt-8 sm:mt-0`}>
                    <Wrapper>
                        <Icon.Share2 size={40} />
                        <Button.Success
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('allocation_limit');
                                setAmount(1);
                            }}
                        >
                            <Icon.Plus />
                        </Button.Success>
                        <Button.Danger
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('allocation_limit');
                                setAmount(-1);
                            }}
                        >
                            <Icon.Minus />
                        </Button.Danger>
                    </Wrapper>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        Cambie el límite de puertos asignados al servidor.
                    </p>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>El límite no puede ser inferior a 1.</p>
                </TitledGreyBox>
                <TitledGreyBox title={'Editar límite de copia de seguridad del servidor'} css={tw`mt-8 sm:mt-0 sm:ml-8`}>
                    <Wrapper>
                        <Icon.Archive size={40} />
                        <Button.Success
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('backup_limit');
                                setAmount(1);
                            }}
                        >
                            <Icon.Plus />
                        </Button.Success>
                        <Button.Danger
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('backup_limit');
                                setAmount(-1);
                            }}
                        >
                            <Icon.Minus />
                        </Button.Danger>
                    </Wrapper>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        Cambie el límite de copias de seguridad asignadas al servidor.
                    </p>
                </TitledGreyBox>
                <TitledGreyBox title={'Editar límite de base de datos del servidor'} css={tw`mt-8 sm:mt-0 sm:ml-8`}>
                    <Wrapper>
                        <Icon.Database size={40} />
                        <Button.Success
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('database_limit');
                                setAmount(1);
                            }}
                        >
                            <Icon.Plus />
                        </Button.Success>
                        <Button.Danger
                            css={tw`ml-4`}
                            onClick={() => {
                                setSubmitting(true);
                                setResource('database_limit');
                                setAmount(-1);
                            }}
                        >
                            <Icon.Minus />
                        </Button.Danger>
                    </Wrapper>
                    <p css={tw`mt-1 text-gray-500 text-xs flex justify-center`}>
                        Cambiar el límite de bases de datos asignadas al servidor.
                    </p>
                </TitledGreyBox>
            </Container>
        </ServerContentBlock>
    );
};

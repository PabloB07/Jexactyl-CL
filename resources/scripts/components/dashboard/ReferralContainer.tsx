import tw from 'twin.macro';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { breakpoint } from '@/theme';
import * as Icon from 'react-feather';
import styled from 'styled-components/macro';
import { useStoreState } from '@/state/hooks';
import React, { useState, useEffect } from 'react';
import { Button } from '@/components/elements/button';
import { Dialog } from '@/components/elements/dialog';
import GreyRowBox from '@/components/elements/GreyRowBox';
import ContentBox from '@/components/elements/ContentBox';
import useFlash, { useFlashKey } from '@/plugins/useFlash';
import deleteReferralCode from '@/api/account/deleteReferralCode';
import createReferralCode from '@/api/account/createReferralCode';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import PageContentBlock from '@/components/elements/PageContentBlock';
import getReferralCodes, { ReferralCode } from '@/api/account/getReferralCodes';
import getReferralActivity, { ReferralActivity } from '@/api/account/getReferralActivity';

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

export default () => {
    const { addFlash } = useFlash();
    const [code, setCode] = useState('');
    const [loading, setLoading] = useState(false);
    const [codes, setCodes] = useState<ReferralCode[]>([]);
    const [activity, setActivity] = useState<ReferralActivity[]>([]);
    const { clearFlashes, clearAndAddHttpError } = useFlashKey('referrals');
    const reward = useStoreState((state) => state.storefront.data?.referrals.reward);

    useEffect(() => {
        clearFlashes();
        setLoading(true);

        getReferralCodes()
            .then((codes) => {
                setCodes(codes);
                setLoading(false);
            })
            .catch((error) => clearAndAddHttpError(error));

        getReferralActivity()
            .then((activity) => {
                setActivity(activity);
                setLoading(false);
            })
            .catch((error) => clearAndAddHttpError(error));
    }, []);

    const doCreation = () => {
        clearFlashes();
        setLoading(true);

        createReferralCode()
            .then(() => {
                getReferralCodes().then((codes) => setCodes(codes));
                addFlash({
                    type: 'success',
                    key: 'referrals',
                    message: 'El código de referido se ha creado.',
                });
            })
            .catch((error) => clearAndAddHttpError(error))
            .then(() => {
                setLoading(false);
            });
    };

    const doDeletion = (code: string) => {
        clearFlashes();
        setLoading(true);

        deleteReferralCode(code)
            .then(() => {
                getReferralCodes().then((codes) => setCodes(codes));
                addFlash({
                    type: 'success',
                    key: 'referrals',
                    message: 'El código de referido se ha eliminado.',
                });
            })
            .catch((error) => clearAndAddHttpError(error))
            .then(() => {
                setLoading(false);
                setCode('');
            });
    };

    return (
        <PageContentBlock
            title={'Indicaciones'}
            description={'Crear un código para compartirla con otros.'}
            showFlashKey={'referrals'}
        >
            <Container className={'lg:grid lg:grid-cols-3 my-10'}>
                <ContentBox title={'Sus códigos e indicaciones'} css={tw`sm:mt-0`}>
                    <Dialog.Confirm
                        title={'Excluir Código de Indicación'}
                        confirm={'Excluir código'}
                        open={!!code}
                        onClose={() => setCode('')}
                        onConfirmed={() => doDeletion(code)}
                    >
                        Los usuarios ya no podrán utilizar esta clave para registrarse.
                    </Dialog.Confirm>
                    <SpinnerOverlay visible={loading} />
                    {codes.length === 0 ? (
                        <p css={tw`text-center my-2`}>
                            {!loading && 'No hay códigos de referencia para esta cuenta.'}
                        </p>
                    ) : (
                        codes.map((code, index) => (
                            <GreyRowBox
                                key={code.code}
                                css={[tw`bg-neutral-900 flex items-center`, index > 0 && tw`mt-2`]}
                            >
                                <Icon.GitBranch css={tw`text-neutral-300`} />
                                <div css={tw`ml-4 flex-1 overflow-hidden`}>
                                    <p css={tw`text-sm break-words`}>{code.code}</p>
                                    <p css={tw`text-2xs text-neutral-300 uppercase`}>
                                        Creado en:&nbsp;
                                        {code.createdAt ? format(code.createdAt, 'MMM do, yyyy HH:mm') : 'Never'}
                                    </p>
                                </div>
                                <button css={tw`ml-4 p-2 text-sm`} onClick={() => setCode(code.code)}>
                                    <Icon.Trash
                                        css={tw`text-neutral-400 hover:text-red-400 transition-colors duration-150`}
                                    />
                                </button>
                            </GreyRowBox>
                        ))
                    )}
                    <Button onClick={() => doCreation()} className={'mt-4'}>
                        Crear
                    </Button>
                </ContentBox>
                <ContentBox title={'Ventajas disponibles'} css={tw`mt-8 sm:mt-0 sm:ml-8`}>
                    <h1 css={tw`text-xl`}>
                        Recibirás <span className={'text-green-500'}>{reward}</span> créditos por cada usuario que
                        usted nomina para este Panel.
                    </h1>
                </ContentBox>
                <ContentBox title={'Usuarios indicados'} css={tw`mt-8 sm:mt-0 sm:ml-8`}>
                    <SpinnerOverlay visible={loading} />
                    {activity.length === 0 ? (
                        <p css={tw`text-center my-2`}>
                            {!loading && 'No hay actividad de referencia para esta cuenta.'}
                        </p>
                    ) : (
                        activity.map((act, index) => (
                            <GreyRowBox
                                key={act.code}
                                css={[tw`bg-neutral-900 flex items-center`, index > 0 && tw`mt-2`]}
                            >
                                <Icon.GitBranch css={tw`text-neutral-300`} />
                                <div css={tw`ml-4 flex-1 overflow-hidden`}>
                                    <p css={tw`text-sm break-words`}>
                                        {act.userEmail} (ID: {act.userId})
                                    </p>
                                    <p css={tw`text-2xs text-neutral-300 uppercase`}>
                                        Usado en:&nbsp;
                                        {act.createdAt
                                            ? format(act.createdAt, "'dia' d 'de' MMMM yyyy', ás' HH:mm", {
                                                  locale: ptBR,
                                              })
                                            : 'Nunca'}
                                    </p>
                                    <p css={tw`text-2xs text-neutral-300 uppercase`}>Usado en:&nbsp;{act.code}</p>
                                </div>
                            </GreyRowBox>
                        ))
                    )}
                </ContentBox>
            </Container>
        </PageContentBlock>
    );
};

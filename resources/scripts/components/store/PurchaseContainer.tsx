import tw from 'twin.macro';
import { breakpoint } from '@/theme';
import styled from 'styled-components/macro';
import { useStoreState } from '@/state/hooks';
import React, { useEffect, useState } from 'react';
import Spinner from '@/components/elements/Spinner';
import ContentBox from '@/components/elements/ContentBox';
import { getResources, Resources } from '@/api/store/getResources';
import PageContentBlock from '@/components/elements/PageContentBlock';
import StripePurchaseForm from '@/components/store/forms/StripePurchaseForm';
import PaypalPurchaseForm from '@/components/store/forms/PaypalPurchaseForm';
import MercadoPagoPurchaseForm from '@/components/store/forms/MercadoPagoPurchaseForm';

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
    const [resources, setResources] = useState<Resources>();
    const earn = useStoreState((state) => state.storefront.data!.earn);
    const paypal = useStoreState((state) => state.storefront.data!.gateways?.paypal);
    const stripe = useStoreState((state) => state.storefront.data!.gateways?.stripe);
    const mpago = useStoreState((state) => state.storefront.data!.gateways?.mpago);

    useEffect(() => {
        getResources().then((resources) => setResources(resources));
    }, []);

    if (!resources) return <Spinner size={'large'} centered />;

    return (
        <PageContentBlock
            title={'Saldo en billetera'}
            description={'Compra creditos a travez de MercadoPago.'}
        >
            <Container className={'lg:grid lg:grid-cols-2 my-10'}>
                <ContentBox title={'Billetera del usuario'} showFlashes={'account:balance'} css={tw`sm:mt-0`}>
                    <h1 css={tw`text-7xl flex justify-center items-center`}>
                        {resources.balance} <span className={'text-base ml-4'}>creditos</span>
                    </h1>
                </ContentBox>
                <ContentBox title={'Comprar Créditos'} showFlashes={'account:balance'} css={tw`mt-8 sm:mt-0 sm:ml-8`}>
                    {paypal && <PaypalPurchaseForm />}
                    {stripe && <StripePurchaseForm />}
                    {mpago && <MercadoPagoPurchaseForm />}
                    {!paypal && !stripe && !mpago && (
                        <p className={'text-gray-400 text-sm m-2'}>
                            Ningun gateway conectado o configurado, intentalo mas tarde.
                        </p>
                    )}
                </ContentBox>
            </Container>
            {earn.enabled && (
                <>
                    <h1 className={'text-5xl'}>Gana de crédito AFK</h1>
                    <h3 className={'text-2xl text-neutral-500'}>
                        Ve cuantos creditos puedes ganar AFK.
                    </h3>
                    <Container className={'lg:grid lg:grid-cols-2 my-10'}>
                        <ContentBox title={'Earn Rate'} showFlashes={'earn:rate'} css={tw`sm:mt-0`}>
                            <h1 css={tw`text-7xl flex justify-center items-center`}>
                                {earn.amount} <span className={'text-base ml-4'}>Créditos / min</span>
                            </h1>
                        </ContentBox>
                        <ContentBox title={'Como ganhar'} showFlashes={'earn:how'} css={tw`mt-8 sm:mt-0 sm:ml-8`}>
                            <p>Puedes ganar creditos con la ventana abierta.</p>
                            <p css={tw`mt-1`}>
                                <span css={tw`text-green-500`}>{earn.amount}&nbsp;</span>
                                crédito(s) por minuto será automáticamente agregado a su cuenta, desde este sitio
                                este aierto en una guía del navegador.
                            </p>
                        </ContentBox>
                    </Container>
                </>
            )}
        </PageContentBlock>
    );
};

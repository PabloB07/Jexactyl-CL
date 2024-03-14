import useFlash from '@/plugins/useFlash';
import apiVerify from '@/api/account/verify';
import { useStoreState } from '@/state/hooks';
import React, { useEffect, useState } from 'react';
import { formatDistanceToNowStrict } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { getResources } from '@/api/store/getResources';
import Translate from '@/components/elements/Translate';
import InformationBox from '@/components/elements/InformationBox';
import getLatestActivity, { Activity } from '@/api/account/getLatestActivity';
import { wrapProperties } from '@/components/elements/activity/ActivityLogEntry';
import {
    faCircle,
    faCoins,
    faExclamationCircle,
    faScroll,
    faTimesCircle,
    faUserLock,
} from '@fortawesome/free-solid-svg-icons';

export default () => {
    const { addFlash } = useFlash();
    const [bal, setBal] = useState(0);
    const [activity, setActivity] = useState<Activity>();
    const properties = wrapProperties(activity?.properties);
    const user = useStoreState((state) => state.user.data!);
    const store = useStoreState((state) => state.storefront.data!);

    useEffect(() => {
        getResources().then((d) => setBal(d.balance));
        getLatestActivity().then((d) => setActivity(d));
    }, []);

    const verify = () => {
        apiVerify().then((data) => {
            if (data.success)
                addFlash({
                    type: 'info',
                    key: 'dashboard',
                    message: 'Se ha enviado el correo electrónico de verificación..',
                });
        });
    };

    return (
        <>
            {store.earn.enabled ? (
                <InformationBox icon={faCircle} iconCss={'animate-pulse'}>
                    Ganando <span className={'text-green-600'}>{store.earn.amount}</span> Créditos / min.
                </InformationBox>
            ) : (
                <InformationBox icon={faExclamationCircle}>
                    Actualmente, las ganancias crediticias están <span className={'text-red-600'}>desactivado.</span>
                </InformationBox>
            )}
            <InformationBox icon={faCoins}>
                Tu tienes <span className={'text-green-600'}>{bal}</span> créditos disponibles.
            </InformationBox>
            <InformationBox icon={faUserLock}>
                {user.useTotp ? (
                    <>
                        <span className={'text-green-600'}>2FA está habilitado</span> en su cuenta
                    </>
                ) : (
                    <>
                        <span className={'text-yellow-600'}>Habilitar 2FA</span> para proteger su cuonta.
                    </>
                )}
            </InformationBox>
            {!user.verified ? (
                <InformationBox icon={faTimesCircle} iconCss={'text-yellow-500'}>
                    <span onClick={verify} className={'cursor-pointer text-blue-400'}>
                        Verifique sua cuonta para comenzar.
                    </span>
                </InformationBox>
            ) : (
                <InformationBox icon={faScroll}>
                    {activity ? (
                        <>
                            <span className={'text-neutral-400'}>
                                <Translate
                                    ns={'activity'}
                                    values={properties}
                                    i18nKey={activity.event.replace(':', '.')}
                                />
                            </span>
                            {' - '}
                            {formatDistanceToNowStrict(activity.timestamp, {
                                addSuffix: true,
                                locale: ptBR,
                            })}
                        </>
                    ) : (
                        'No se pueden obtener los registros de actividad más recientes.'
                    )}
                </InformationBox>
            )}
        </>
    );
};

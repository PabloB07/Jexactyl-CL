import tw from 'twin.macro';
import Reaptcha from 'reaptcha';
import login from '@/api/auth/login';
import { object, string, setLocale } from 'yup';
import { pt } from 'yup-locales';
import useFlash from '@/plugins/useFlash';
import { useStoreState } from 'easy-peasy';
import { Formik, FormikHelpers } from 'formik';
import Field from '@/components/elements/Field';
import React, { useEffect, useRef, useState } from 'react';
import { Button } from '@/components/elements/button/index';
import { Link, RouteComponentProps } from 'react-router-dom';
import LoginFormContainer from '@/components/auth/LoginFormContainer';

interface Values {
    username: string;
    password: string;
}

const LoginContainer = ({ history }: RouteComponentProps) => {
    const ref = useRef<Reaptcha>(null);
    const [token, setToken] = useState('');
    const name = useStoreState((state) => state.settings.data?.name);
    const email = useStoreState((state) => state.settings.data?.registration.email);
    const discord = useStoreState((state) => state.settings.data?.registration.discord);

    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { enabled: recaptchaEnabled, siteKey } = useStoreState((state) => state.settings.data!.recaptcha);

    setLocale(pt);

    useEffect(() => {
        clearFlashes();
    }, []);

    const onSubmit = (values: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes();

        // If there is no token in the state yet, request the token and then abort this submit request
        // since it will be re-submitted when the recaptcha data is returned by the component.
        if (recaptchaEnabled && !token) {
            ref.current!.execute().catch((error) => {
                console.error(error);

                setSubmitting(false);
                clearAndAddHttpError({ error });
            });

            return;
        }

        login({ ...values, recaptchaData: token })
            .then((response) => {
                if (response.complete) {
                    // @ts-expect-error this is valid
                    window.location = response.intended || '/';
                    return;
                }

                history.replace('/auth/login/checkpoint', {
                    token: response.confirmationToken,
                });
            })
            .catch((error) => {
                console.error(error);

                setToken('');
                if (ref.current) ref.current.reset();

                setSubmitting(false);
                clearAndAddHttpError({ error });
            });
    };

    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={{ username: '', password: '' }}
            validationSchema={object().shape({
                username: string().required('Se debe proporcionar un nombre de usuario o correo electrónico.'),
                password: string().required('Por favor ingrese la contraseña de su cuenta.'),
            })}
        >
            {({ isSubmitting, setSubmitting, submitForm }) => (
                <LoginFormContainer title={'Inicie sesión en el panel ' + name} css={tw`w-full flex`}>
                    <Field light type={'text'} label={'Usuario de E-mail'} name={'username'} disabled={isSubmitting} />
                    <div css={tw`mt-6`}>
                        <Field light type={'password'} label={'Contraseña'} name={'password'} disabled={isSubmitting} />
                    </div>
                    <div css={tw`mt-6`}>
                        <Button type={'submit'} size={Button.Sizes.Large} css={tw`w-full`} disabled={isSubmitting}>
                            Iniciar
                        </Button>
                    </div>
                    {recaptchaEnabled && (
                        <Reaptcha
                            ref={ref}
                            size={'invisible'}
                            sitekey={siteKey || '_invalid_key'}
                            onVerify={(response) => {
                                setToken(response);
                                submitForm();
                            }}
                            onExpire={() => {
                                setSubmitting(false);
                                setToken('');
                            }}
                        />
                    )}
                    <div css={tw`mt-6 text-center`}>
                        <Link
                            to={'/auth/password'}
                            css={tw`text-xs text-neutral-500 tracking-wide no-underline uppercase hover:text-neutral-600`}
                        >
                            Se te olvido la contraseña?
                        </Link>
                    </div>
                    <div css={tw`mt-6 text-center`}>
                        {email && (
                            <Link
                                to={'/auth/register'}
                                css={tw`text-xs text-neutral-500 tracking-wide no-underline uppercase hover:text-neutral-600`}
                            >
                                Registrar con E-mail
                            </Link>
                        )}
                        {discord && (
                            <Link
                                to={'/auth/discord'}
                                css={tw`text-xs text-neutral-500 tracking-wide no-underline uppercase hover:text-neutral-600`}
                            >
                                Entrar con Discord
                            </Link>
                        )}
                    </div>
                </LoginFormContainer>
            )}
        </Formik>
    );
};

export default LoginContainer;

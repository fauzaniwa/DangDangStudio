tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandDark: '#333A73',
                        brandCoral: '#FF6136',
                        brandTeal: '#019E9A',
                        brandGold: '#FEA302',
                    },
                    borderRadius: {
                        '4xl': '2.5rem',
                        '5xl': '4.5rem'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'marquee': 'marquee 30s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px) rotate(3deg)'
                            },
                            '50%': {
                                transform: 'translateY(-20px) rotate(0deg)'
                            },
                        },
                        marquee: {
                            '0%': {
                                transform: 'translateX(0)'
                            },
                            '100%': {
                                transform: 'translateX(-50%)'
                            },
                        }
                    }
                }
            }
        }
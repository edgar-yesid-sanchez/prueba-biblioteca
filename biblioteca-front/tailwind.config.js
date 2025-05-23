module.exports = {
  content: [
    "./src/**/*.{html,ts,css}", // Ajusta las rutas según tu proyecto
  ],
  theme: {
    extend: {
      animation: {
        'modal-in': 'fadeIn 0.3s ease-out forwards',
        'modal-out': 'fadeOut 0.25s ease-in forwards',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: 0, transform: 'scale(0.95)' },
          '100%': { opacity: 1, transform: 'scale(1)' }
        },
        fadeOut: {
          '0%': { opacity: 1, transform: 'scale(1)' },
          '100%': { opacity: 0, transform: 'scale(0.95)' }
        }
      }
    }

  },
  plugins: [],
};